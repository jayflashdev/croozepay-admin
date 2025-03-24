<?php

namespace App\Services;

use App\Models\{
    Beneficiary,
    DataBundle,
    DataPlan,
    Network,
    Transaction,
    User
};
use App\Process\DataProcess;
use Auth;
use DB;

class DataService
{
    private $dataProcess;

    public function __construct(DataProcess $dataProcess)
    {
        $this->dataProcess = $dataProcess;
    }

    // Handle Purchase
    public function processData($user, $payload, $system ="WEB")
    {
        // check if service is anabled
        if (sys_setting('is_data') != 1) {
            return [
                'body' => ['status' => 'error', 'message' => 'Data service is currently disabled. Please try again.'],
                'http_code' => 400,
            ];
        }
        $network = Network::findOrFail($payload['network']);
        if ($network->data != 1) {
            return [
                'body' => ['status' => 'error', 'message' => 'Data is disabled for this network.'],
                'http_code' => 400,
            ];
        }
        // Get the data plan and network details
        $plan = DataPlan::findOrFail($payload['plan']);
        if ($plan->status != 1) {
            return [
                'body' => ['status' => 'error', 'message' => 'This plan is currently disabled.'],
                'http_code' => 400,
            ];
        }
        // if plan type is disabled from admin
        if ( ($plan->type == 'SME' && $plan->network->data_sme != 1) || ($plan->type == 'GIFTING' && $plan->network->data_g != 1) || ($plan->type == 'CG' && $plan->network->data_cg != 1) ) {
            return [
                'body' => ['status' => 'error', 'message' => strtoupper($plan->type) . " Data is currently disabled."],
                'http_code' => 400,
            ];
        }


        if ($user->type == 'reseller') {
            $plan->price = $plan->reseller;
        }
        if($system == "API"){
            $plan->price = $plan->api;
        }

        if ($user->balance < $plan->price) {
            return [
                'body' => ['status' => 'error', 'message' => 'Insufficient Balance. Fund your wallet and try again.'],
                'http_code' => 400,
            ];
        }
        $cost = $plan->price;
        $network = $plan->network;

        DB::beginTransaction();
        try {
            if ($payload['saveBenef'] ?? false) {
                $this->saveBeneficiary($payload['phone']);
            }

            // Prepare transaction metadata
            $meta = [
                'amount' => $cost,
                'phone' => $payload['phone'],
                'network' => $plan->network->name,
                'plan' => $plan->name." ".$plan->size." - ".$plan->day,
            ];

            // Create a transaction
            $transaction = $this->createTransaction($user, $payload, $cost, $plan, $meta, $system );

            // Debit the user
            debitUser($user, $cost);
            $user->save();

            $requestData = [
                'network' => $network->id,
                'amount' => $cost,
                'phone' => $payload['phone'],
                'plan' => $plan->id,
                'ref' => $transaction->code,
            ];
            // Send request to the external API
            $apiResponse = $this->dataProcess->purchaseData($requestData);

            if (isset($apiResponse['api_status']) && $apiResponse['api_status'] === "success") {
                $res = $this->handleSuccessfulResponse($transaction, $apiResponse);
                DB::commit();
                return [
                    'body' => [
                        'status' => 'success',
                        'message' =>  $transaction->message ,
                        'ref' => $transaction->reference,
                        'amount' => $transaction->amount,
                        'network' => $network->name,
                        'plan' => $plan->name." ".$plan->size." - ".$plan->day,
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
                        'network' => $network->name,
                        'plan' => $plan->name." ".$plan->size." - ".$plan->day,
                        'number' => $transaction->number,
                        'oldbal' => price_number($transaction->old_balance),
                        'newbal' => price_number($transaction->new_balance),
                    ],
                    'http_code' => 200,
                ];
            }
        } catch (\Exception $e) {
            $this->handleFailedResponse($transaction, $e->getMessage(), $user, $cost);
            DB::commit();
            \Log::error("Data Purchase Error: " . $e->getMessage());
            return [
                'body' => ['status' => 'error', 'message' => 'Something went wrong. Please try again.'],
                'http_code' => 500,
            ];
        }
    }

    // Create a new transaction
    private function createTransaction($user, $payload,$cost, $plan, $meta, $system = "WEB")
    {
        $code = getTrans('DATA');
        $refId = $payload['ref_id'] ?? null;
        if($refId){
            $ref = $payload['ref_id'];
        }else{
            $ref = $code;
        }
        $planName = $plan->network->name.' '.$plan->name." ".$plan->size." - ".$plan->day;

        $transaction = new Transaction();
        $transaction->user_id = $user->id;
        $transaction->type = "debit";
        $transaction->code = $code;
        $transaction->message = "Purchase of {$planName} to {$payload['phone']}";
        $transaction->amount = $cost;
        $transaction->number = $payload['phone'];
        $transaction->status = "processing";
        $transaction->title = "{$planName} Data";
        $transaction->session_id = generateSessionId();
        $transaction->image = my_asset($plan->network->image);
        $transaction->charge = 0;
        $transaction->service = "data";
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
        $msg2 = "Purchase of {$transaction->title} for {$transaction->number} was successful";
        $profit = $transaction->amount - $apiResponse['amount'];
        $transaction->profit = $profit;
        $transaction->api_name = $apiResponse['name'];
        $transaction->message = $apiResponse['message'] ?? $msg2;
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
        $transaction->message = "Purchase of {$transaction->title} for {$transaction->number} was not successful";
        $transaction->save();

        creditUser($user, $cost);
        $user->save();
    }

    function saveBeneficiary($number, $type = 'network', $name = null)
    {
        $user = User::where('id', Auth::id())->lockForUpdate()->first();
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
