<?php

namespace App\Services;

use App\Models\{
    Beneficiary,
    Electricity,
    Transaction,
    User
};
use App\Process\PowerProcess;
use Auth;
use DB;

class PowerService
{
    private $powerProcess;

    public function __construct(PowerProcess $powerProcess)
    {
        $this->powerProcess = $powerProcess;
    }

    // Process the power purchase transaction, including validation, creating a transaction record, debiting the user, and handling the response from the external API.
    /**
     * Process the power purchase transaction.
     *
     * @param User $user The user making the purchase.
     * @param array $payload The data for the purchase.
     * @param string $system The system making the request (default: "WEB").
     * @return array The response body and HTTP code.
     */
    public function processPower($user, $payload, $system = "WEB")
    {
        // check if service is anabled
        if (sys_setting('is_electricity') != 1) {
            return [
                'body' => ['status' => 'error', 'message' => 'Electricity service is currently disabled. Please try again.'],
                'http_code' => 400,
            ];
        }
        $disco = Electricity::findOrFail($payload['disco']);
        if ($disco->status != 1) {
            return [
                'body' => ['status' => 'error', 'message' => 'This plan is currently disabled.'],
                'http_code' => 400,
            ];
        }
        $cost = $payload['amount'] + $disco->fee;
        if ($disco->minimum > $payload['amount']) {
            return [
                'body' => [
                    'status' => 'error',
                    'message' => 'Amount is lower than minimum price ' . format_price($disco->minimum),
                ],
                'http_code' => 400,
            ];
        }
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
                $this->saveBeneficiary($payload['meter_number'],'power' ,$payload['customer_name']);
            }

            // Prepare transaction metadata
            $meta = [
                'disco' => $disco->name,
                'number' => $payload['meter_number'],
                'amount' => $payload['amount'],
                'customer_name' => $payload['customer_name'] ?? 'Bypass User',
            ];

            // Create a transaction
            $transaction = $this->createTransaction($user, $payload, $disco, $cost, $meta, $system );

            // Debit the user
            debitUser($user, $cost);
            // $user->refresh();

            $requestData = [
                'amount' => $payload['amount'],
                'phone' => $payload['phone'],
                'name' => $payload['customer_name'],
                'number' => $payload['meter_number'],
                'type' => $payload['meter_type'],
                'disco' => $disco->id,
                'ref' => $transaction->code,
            ];
            // Send request to the external API
            $apiResponse = $this->powerProcess->purchasePower($requestData);

            if (isset($apiResponse['api_status']) && $apiResponse['api_status'] === "success") {
                $res = $this->handleSuccessfulResponse($transaction, $apiResponse);
                DB::commit();
                return [
                    'body' => [
                        'status' => 'success',
                        'message' =>  $transaction->message ,
                        'ref' => $transaction->reference,
                        'amount' => $transaction->amount,
                        'disco' => $disco->name,
                        'token' => $transaction->token,
                        'meter_number' => $transaction->number,
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
                        'disco' => $disco->name,
                        'meter_number' => $transaction->number,
                        'token' => $transaction->token,
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
            \Log::error("Power Purchase Error: " . $e->getMessage());
            return [
                'body' => ['status' => 'error', 'message' => 'Something went wrong. Please try again.'],
                'http_code' => 500,
            ];
        }
    }

    // Create a new transaction
    private function createTransaction($user, $payload, $disco, $cost, $meta, $system = "WEB")
    {
        $code = getTrans('POWER');
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
        $transaction->message = "Purchase of {$disco->name} " . format_price($payload['amount']) . " for {$payload['meter_number']}";
        $transaction->amount = $cost;
        $transaction->number = $payload['meter_number'];
        $transaction->status = "processing";
        $transaction->title = "Buy Power";
        $transaction->session_id = generateSessionId();
        $transaction->image = my_asset($disco->image);
        $transaction->charge = 0;
        $transaction->service = "power";
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
        $transaction->message = "Successfully purchased {$transaction->meta->disco} " . format_price($transaction->meta->amount) . " for {$transaction->number}";
        $transaction->status = "successful";
        $transaction->response = $apiResponse;
        // save token
        $transaction->token = $apiResponse['token'] ?? null;
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
        $transaction->message = "Purchase of {$transaction->meta->disco} ₦{$transaction->meta->amount} to {$transaction->number} was not successful";
        $transaction->save();

        $user->refresh();
        creditUser($user, $cost);
    }

    function saveBeneficiary($number, $type = 'power', $name = null)
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
