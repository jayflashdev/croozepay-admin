<?php

namespace App\Services;

use App\Models\{
    Beneficiary,
    Network,
    Transaction,
    User
};
use App\Process\AirtimeProcess;
use Auth;
use DB;

class AirtimeService
{
    private $airtimeProcess;

    public function __construct(AirtimeProcess $airtimeProcess)
    {
        $this->airtimeProcess = $airtimeProcess;
    }

    // Handle Purchase
    public function processTopUp($user, $payload, $system ="WEB")
    {
        // check if service is anabled
        if (sys_setting('is_airtime') != 1) {
            return [
                'body' => ['status' => 'error', 'message' => 'Airtime service is currently disabled. Please try again.'],
                'http_code' => 400,
            ];
        }
        $network = Network::findOrFail($payload['network']);
        if ($network->airtime != 1) {
            return [
                'body' => ['status' => 'error', 'message' => 'Airtime is disabled for this network.'],
                'http_code' => 400,
            ];
        }
        $cost = $payload['amount'] * ($network->discount / 100);
        if ($user->type == 'reseller') {
            $cost = $payload['amount'] * ($network->reseller / 100);
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
                $this->saveBeneficiary($payload['phone']);
            }

            // Prepare transaction metadata
            $meta = [
                'amount' => $payload['amount'],
                'phone' => $payload['phone'],
                'network' => $network->name,
            ];

            // Create a transaction
            $transaction = $this->createTransaction($user, $payload, $network, $cost, $meta,  $system );

            // Debit the user
            debitUser($user, $cost);
            $user->save();
            $requestData = [
                'amount' => $payload['amount'],
                'phone' => $payload['phone'],
                'network' => $network->id,
                'ref' => $transaction->code,
            ];
            // Send request to the external API
            $apiResponse = $this->airtimeProcess->purchaseAirtime($requestData);

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
            \Log::error("Airtime Purchase Error: " . $e->getMessage());
            return [
                'body' => ['status' => 'error', 'message' => 'Something went wrong. Please try again.'],
                'http_code' => 500,
            ];
        }
    }

    // Create a new transaction
    private function createTransaction($user, $payload, $network, $cost, $meta, $system = "WEB")
    {
        $code = getTrans('AIRTIME');
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
        $transaction->message = "Purchase of {$network->name} Airtime worth " . format_price($payload['amount']) . " for {$payload['phone']}";
        $transaction->amount = $cost;
        $transaction->number = $payload['phone'];
        $transaction->status = "processing";
        $transaction->title = "{$network->name} Airtime";
        $transaction->session_id = generateSessionId();
        $transaction->image = my_asset($network->image);
        $transaction->charge = 0;
        $transaction->service = "airtime";
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
        $transaction->message = "Successfully purchased {$transaction->meta->network} Airtime worth " . format_price($transaction->meta->amount) . " for {$transaction->number}";
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
        $transaction->message = "Airtime Purchase of {$transaction->meta->network} ₦{$transaction->meta->amount} to {$transaction->number} was not successful";
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
