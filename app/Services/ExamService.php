<?php

namespace App\Services;

use App\Models\{
    Beneficiary,
    Education,
    EduTrx,
    Transaction,
    User
};
use App\Process\ExamProcess;
use Auth;
use DB;

class ExamService
{
    private $examProcess;

    public function __construct(ExamProcess $examProcess)
    {
        $this->examProcess = $examProcess;
    }

    // Handle Purchase
    public function processExam($user, $payload, $system ="WEB")
    {
        // check if service is anabled
        if (sys_setting('is_education') != 1) {
            return [
                'body' => ['status' => 'error', 'message' => 'Exam Pin service is currently disabled. Please try again.'],
                'http_code' => 400,
            ];
        }
        $plan = Education::findOrFail($payload['exam']);
        if ($plan->status != 1) {
            return [
                'body' => ['status' => 'error', 'message' => 'This plan is currently disabled.'],
                'http_code' => 400,
            ];
        }
        if ($payload['quantity'] > 5) {
            return [
                'body' => ['status' => 'error', 'message' => 'Quantity must not be more than 5.'],
                'http_code' => 400,
            ];
        }
        if ($user->type == 'reseller') {
            $plan->price = $plan->reseller;
        }
        if($system == "API"){
            $plan->price = $plan->api;
        }
        $cost =  $payload['quantity'] *  $plan->price;
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

            // Prepare transaction metadata
            $meta = [
                'quantity' => $payload['quantity'],
                'amount' => $cost,
                'exam' => $plan->name,
                'exam_id' => $plan->id,
            ];

            // Create a transaction
            $transaction = $this->createTransaction($user, $payload, $plan, $cost, $meta,  $system );

            // Debit the user
            $user->refresh();
            debitUser($user, $cost);
            $requestData = [
                'name' => strtoupper($plan['code']),
                'amount' => $cost,
                'quantity' => $payload['quantity'],
                'phone' => $payload['phone'] ?? $user->phone,
                'exam' => $plan->id,
                'ref' => $transaction->code,
            ];
            // Send request to the external API
            $apiResponse = $this->examProcess->purchaseExam($requestData);

            if (isset($apiResponse['api_status']) && $apiResponse['api_status'] === "success") {
                $res = $this->handleSuccessfulResponse($transaction, $apiResponse);
                DB::commit();

                return [
                    'body' => [
                        'status' => 'success',
                        'message' =>  $transaction->message ,
                        'ref' => $transaction->reference,
                        'amount' => $transaction->amount,
                        'exam' => $plan->name,
                        'pins' => $apiResponse['pin'],
                        'serial' => $apiResponse['serial'] ?? null,
                        'session_id' => $transaction->session_id,
                        'oldbal' => price_number($transaction->old_balance),
                        'newbal' => price_number($transaction->new_balance),
                    ],
                    'http_code' => 200,
                    'transaction' => $transaction,
                ];
            } else {
                $this->handleFailedResponse($transaction, $apiResponse, $user, $cost);
                //
                DB::commit();
                return [
                    'body' => [
                        'status' => 'error',
                        'message' =>  $transaction->message ,
                        'ref' => $transaction->reference,
                        'amount' => $transaction->amount,
                        'exam' => $plan->name,
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
            \Log::error("Exam Purchase Error: " . $e);
            return [
                'body' => ['status' => 'error', 'message' => 'Something went wrong. Please try again.'],
                'http_code' => 500,
            ];
        }
    }

    // Create a new transaction
    private function createTransaction($user, $payload, $plan, $cost, $meta, $system = "WEB")
    {
        $code = getTrans('EXAM');
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
        $transaction->message = "Purchase of {$plan->name} Pins";
        $transaction->amount = $cost;
        $transaction->number = $payload['phone'] ?? '';
        $transaction->status = "processing";
        $transaction->title = "{$plan->name} Exam";
        $transaction->session_id = generateSessionId();
        $transaction->image = my_asset($plan->image);
        $transaction->charge = 0;
        $transaction->service = "exam";
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
        $transaction->message = $transaction->message . " was successful";
        $transaction->status = "successful";
        $transaction->response = $apiResponse;
        $transaction->save();

        // create edu trx
        $trx = new EduTrx();
        $trx->user_id = $transaction->user_id;
        $trx->education_id = $transaction->meta->exam_id;
        $trx->code = $transaction->code;
        $trx->quantity = $transaction->meta->quantity;
        $trx->name = $transaction->message;
        $trx->amount = $transaction->amount;
        $trx->status = 1; //1 - success , 2- pending, 3 -declined
        $trx->charge = 0;
        $trx->response = json_encode($apiResponse);
        $trx->pins = $apiResponse['pin'];
        $trx->serial = $apiResponse['serial'] ?? null;
        $trx->old_balance = $transaction->old_balance;
        $trx->new_balance = $transaction->new_balance;
        $trx->save();
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
        $transaction->message = $transaction->message . " was not successful";
        $transaction->save();

        $user->refresh();
        creditUser($user, $cost);
    }
}
