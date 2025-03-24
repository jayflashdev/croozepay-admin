<?php

namespace App\Services;

use App\Models\{
    Beneficiary,
    Betsite,
    Network,
    Transaction,
    User
};
use App\Process\BetProcess;
use Auth;
use DB;

class BetService
{
    private $betProcess;

    public function __construct(BetProcess $betProcess)
    {
        $this->betProcess = $betProcess;
    }

    /**
     * Process the bet purchase transaction.
     *
     * @param User $user The user making the purchase.
     * @param array $payload The data for the purchase.
     * @param string $system The system making the request (default: "WEB").
     * @return array The response body and HTTP code.
     */
    public function processBet($user, $payload, $system = "WEB")
    {
        // check if service is anabled
        if (sys_setting('is_betting') != 1) {
            return [
                'body' => ['status' => 'error', 'message' => 'Betting service is currently disabled. Please try again.'],
                'http_code' => 400,
            ];
        }
        $plan = Betsite::findOrFail($payload['plan']);
        if ($plan->status != 1) {
            return [
                'body' => ['status' => 'error', 'message' => 'This plan is currently disabled.'],
                'http_code' => 400,
            ];
        }
        $cost = $payload['amount'] + $plan->fee;
        if ($plan->minimum > $payload['amount']) {
            return [
                'body' => [
                    'status' => 'error',
                    'message' => 'Amount is lower than minimum price ' . format_price($plan->minimum),
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
                $this->saveBeneficiary($payload['number'],'bet' ,$payload['customer_name']);
            }

            // Prepare transaction metadata
            $meta = [
                'plan' => $plan->name,
                'number' => $payload['number'],
                'amount' => $payload['amount'],
                'customer_name' => $payload['customer_name'] ?? 'Bypass User',
            ];

            // Create a transaction
            $transaction = $this->createTransaction($user, $payload, $plan, $cost, $meta, $system );

            // Debit the user
            debitUser($user, $cost);
            // $user->refresh();
            $requestData = [
                'amount' => $payload['amount'],
                'number' => $payload['number'],
                'phone' => $payload['phone'] ?? $user->phone,
                'name' => $payload['customer_name'] ?? "Bypass User",
                'plan' => $plan->id,
                'ref' => $transaction->code,
            ];
            // Send request to the external API
            $apiResponse = $this->betProcess->purchaseBet($requestData);
            if (isset($apiResponse['api_status']) && $apiResponse['api_status'] === "success") {
                $res = $this->handleSuccessfulResponse($transaction, $apiResponse);

                DB::commit();
                return [
                    'body' => [
                        'status' => 'success',
                        'message' =>  $transaction->message ,
                        'ref' => $transaction->reference,
                        'amount' => $transaction->amount,
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
                        'plan' => $plan->name,
                        'number' => $transaction->number,
                        'oldbal' => price_number($transaction->old_balance),
                        'newbal' => price_number($transaction->new_balance),
                    ],
                    'http_code' => 200,
                ];
            }
        } catch (\Exception $e) {
            \Log::error("Bet Purchase Error: " . $e->getMessage());
            $this->handleFailedResponse($transaction, $e->getMessage(), $user, $cost);
            DB::commit();
            return [
                'body' => ['status' => 'error', 'message' => 'Something went wrong. Please try again.'],
                'http_code' => 500,
            ];
        }
    }

    // Create a new transaction
    private function createTransaction($user, $payload, $plan, $cost, $meta, $system = "WEB")
    {
        $code = getTrans('BET');
        $ref = $payload['ref_id'] ?? $code;

        $transaction = new Transaction();
        $transaction->user_id = $user->id;
        $transaction->type = "debit";
        $transaction->code = $code;
        $transaction->message = "{$plan->name} Purchase of " . format_price($payload['amount']) . " for {$payload['number']}";
        $transaction->amount = $cost;
        $transaction->number = $payload['number'];
        $transaction->status = "processing";
        $transaction->title = "{$plan->name} Funding";
        $transaction->session_id = generateSessionId();
        $transaction->image = my_asset($plan->image);
        $transaction->charge = 0;
        $transaction->service = "betting";
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
        $transaction->message =
        $transaction->message = $transaction->message . " was successful.";
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
        $transaction->message = $transaction->message . " was not successful";
        $transaction->save();

        $user->refresh();
        creditUser($user, $cost);
    }

    function saveBeneficiary($number, $type = 'bet', $name = null)
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
