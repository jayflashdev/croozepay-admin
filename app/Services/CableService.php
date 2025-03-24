<?php

namespace App\Services;

use App\Models\{
    Beneficiary,
    CablePlan,
    Decoder,
    Transaction,
    User
};
use App\Process\CableProcess;
use Auth;
use DB;

class CableService
{
    private $cableProcess;

    public function __construct(CableProcess $cableProcess)
    {
        $this->cableProcess = $cableProcess;
    }

    // Handle Purchase
    public function processCable($user, $payload, $system ="WEB")
    {
        // check if service is anabled
        if (sys_setting('is_cable') != 1) {
            return [
                'body' => ['status' => 'error', 'message' => 'Cable service is currently disabled. Please try again.'],
                'http_code' => 400,
            ];
        }
        // Get the cable plan and decoder details
        $cable = Decoder::findOrFail($payload['cable']);
        if ($cable->status != 1) {
            return [
                'body' => ['status' => 'error', 'message' => 'Service is disabled for this cable.'],
                'http_code' => 400,
            ];
        }
        $plan = CablePlan::findOrFail($payload['cable_plan']);
        if ($plan->status != 1) {
            return [
                'body' => ['status' => 'error', 'message' => 'This plan is currently disabled.'],
                'http_code' => 400,
            ];
        }

        if ($user->type == 'reseller') {
            $plan->price = $plan->reseller;
        }
        if($system == "API"){
            $plan->price = $plan->api;
        }
        $cost = $plan->price;
        if ($user->balance < $cost) {
            return [
                'body' => [
                    'status' => 'error',
                    'message' => "Insufficient Balance. Fund Your Wallet. Balance: " . format_price($user->balance),
                ],
                'http_code' => 400,
            ];
        }
        DB::beginTransaction();
        try {
            // Save as Beneficiary if requested
            if ($payload['saveBenef'] ?? false) {
                $this->saveBeneficiary($payload['number'], 'cable', $payload['customer_name']);
            }

            // Prepare transaction metadata
            $meta = [
                'cable' => $cable['name'],
                'plan' => $plan->name,
                'amount' => $cost,
                'phone' => $payload['phone'] ?? $user->phone,
                'number' => $payload['number'],
                'customer_name' => $payload['customer_name'] ?? 'Bypass User',
            ];

            // Create a transaction
            $transaction = $this->createTransaction($user, $payload, $plan, $cost, $meta,  $system );

            // Debit the user
            debitUser($user, $cost);
            $user->save();
            $requestData = [
                'amount' => $cost,
                'phone' => $payload['phone'] ?? $user->phone,
                'name' => $payload['customer_name'] ?? "Bypass User",
                'number' => $payload['number'],
                'plan' => $plan['id'],
                'cable' => $cable->id,
                'ref' => $transaction->code,
            ];
            // Send request to the external API
            $apiResponse = $this->cableProcess->purchaseCable($requestData);

            if (isset($apiResponse['api_status']) && $apiResponse['api_status'] === "success") {
                $res = $this->handleSuccessfulResponse($transaction, $apiResponse);
                DB::commit();
                return [
                    'body' => [
                        'status' => 'success',
                        'message' =>  $transaction->message ,
                        'ref' => $transaction->reference,
                        'amount' => $transaction->amount,
                        'cable' => $cable->name,
                        'plan' => $plan->name,
                        'number' => $transaction->number,
                        'session_id' => $transaction->session_id,
                        'oldbal' => price_number($transaction->old_balance),
                        'newbal' => price_number($transaction->new_balance),
                    ],
                    'http_code' => 200,
                    'transaction' => $transaction,
                ];
            } else {
                $this->handleFailedResponse($transaction, $apiResponse, $user, $cost);
                DB::commit();
                return [
                    'body' => [
                        'status' => 'error',
                        'message' =>  $transaction->message ,
                        'ref' => $transaction->reference,
                        'amount' => $transaction->amount,
                        'cable' => $cable->name,
                        'plan' => $plan->name,
                        'number' => $transaction->number,
                        'oldbal' => price_number($transaction->old_balance),
                        'newbal' => price_number($transaction->new_balance),
                    ],
                    'http_code' => 200,
                ];
                // return ['status' => 'error', 'message' => $transaction->message];
            }
        } catch (\Exception $e) {
            $this->handleFailedResponse($transaction, $e->getMessage(), $user, $cost);
            DB::commit();
            \Log::error("Cable Purchase Error: " . $e->getMessage());
            return [
                'body' => ['status' => 'error', 'message' => 'Something went wrong. Please try again.'],
                'http_code' => 500,
            ];
        }
    }

    // Create a new transaction
    private function createTransaction($user, $payload, $plan, $cost, $meta, $system = "WEB")
    {
        $cable = $plan->decoder;
        $code = getTrans('CABLE');
        $refId = $payload['ref_id'] ?? null;
        if($refId){
            $ref = $payload['ref_id'];
        }else{
            $ref = $code;
        }
        $transaction = new Transaction();
        $transaction->user_id = $user->id;
        $transaction->type = "debit";
        $transaction->code = $code;
        $transaction->message = "Purchase of {$plan->name} for {$payload['number']}";
        $transaction->amount = $cost;
        $transaction->number = $payload['number'];
        $transaction->status = "processing";
        $transaction->title = "{$cable->name} Cable";
        $transaction->session_id = generateSessionId();
        $transaction->image = my_asset($cable->image);
        $transaction->charge = 0;
        $transaction->service = "cable";
        $transaction->old_balance = $user->balance;
        $transaction->new_balance = $user->balance - $cost;
        $transaction->meta = $meta;
        $transaction->system = $system;
        $transaction->reference = $ref;
        $transaction->save();

        return $transaction;
    }

    private function handleSuccessfulResponse($transaction, $apiResponse)
    {
        $profit = $transaction->amount - $apiResponse['amount'];
        $transaction->profit = $profit;
        $transaction->api_name = $apiResponse['name'];
        $transaction->message =  $transaction->message . " was Successful";
        $transaction->status = "successful";
        $transaction->response = $apiResponse;
        $transaction->save();
        // give referral bonus and email
        $user = $transaction->user;
        if (\sys_setting('trx_email') == 1 && $user->email_notify == 1) {
            sendTransactionEmail($user, $transaction);
        }
        // give referral bonus
        if (sys_setting('is_affiliate') == 1) {
            give_affiliate_bonus($user->id, $transaction->amount);
        }
    }

    private function handleFailedResponse($transaction, $apiResponse, $user, $cost)
    {
        $transaction->new_balance = $transaction->old_balance;
        $transaction->status = "failed";
        $transaction->api_name = $apiResponse['name'] ?? null;
        $transaction->response = $apiResponse;
        $transaction->message =  $transaction->message . " was not Successful";
        $transaction->save();

        $user->refresh();
        creditUser($user, $cost);
    }

    function saveBeneficiary($number, $type = 'cable', $name = null)
    {
        $user = User::where('id', Auth::id())->first();
        $b = Beneficiary::updateOrCreate(
            [
                'user_id' => $user->id,
                'number'  => $number,
            ],
            [
                'type'  => $type,
                'name'  => $name ?? null,
            ]
        );
        return $b;
    }
}
