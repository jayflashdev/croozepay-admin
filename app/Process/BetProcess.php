<?php

namespace App\Process;

use App\Models\Betsite;
use App\Utility\{AdexUtility, Clubkonnect, Easyaccess, MsorgUtility, Ncwallet, Vtpass};

class BetProcess
{
    public function purchaseBet($data)
    {
        // External API logic
        $plan = Betsite::find($data['plan']);
        $provider = api_setting('betting_sel');

        try {

            // Ncwallet Provider
            if ($provider == 'ncwallet') {
                $slot = new Ncwallet();
                $payload = [
                    'bypass' => false,
                    'request-id' => $data['ref'],
                    'number' => $data['number'],
                    'betsite' => $plan->ncwallet,
                    'amount' => $data['amount'],
                ];
                $api_result = $slot->buyBet($payload);
                if(isset($api_result['status']) && $api_result['status'] == "success" || $api_result['status'] == "pending"){
                    return [
                        'name' => "ncwallet",
                        'ref' => $data['ref'],
                        'api_status' => "success",
                        'response' => $api_result,
                        'amount' => preg_replace('/[^0-9.]/', '', $api_result['amount_paid']) ?? $data['amount'] ?? 0,
                        'v_ref' => $api_result['ref_id'] ?? "",
                    ];
                } else {
                    return [
                        'ref' => $data['ref'],
                        'name' => "ncwallet",
                        'api_status' => "error",
                        'response' => $api_result,
                        'amount' => $api_result['amount'] ?? $data['amount'],
                    ];
                }
            }
            // Clubkonnect
            else if ($provider == 'clubkonnect') {
                $vend = new Clubkonnect();

                $payload = [
                    "Amount"=> $data['amount'],
                    "CustomerID" => $data["number"],
                    "BettingCompany" => $plan['clubkonnect'],
                    'CallBackURL' => route('api.callback.clubkonnect'),
                    "RequestID" => $data['ref']
                ];

                $api_result = $vend->buyBet($payload);
                if (isset($api_result['status']) && $api_result['status'] == "ORDER_RECEIVED") {
                    return [
                        'name' => "clubkonnect",
                        'ref' => $data['ref'],
                        'api_status' => "success",
                        'response' => $api_result,
                        'message' => $api_result['message'] ?? '',
                        'amount' => $api_result['amount'] ?? $data['amount'],
                        'v_ref' => $api_result['transactionid'] ?? "",
                    ];
                } else {
                    return [
                        'name' => "clubkonnect",
                        'ref' => $data['ref'],
                        'api_status' => "error",
                        'response' => $api_result,
                        'amount' => $api_result['amount'] ?? $data['amount'],
                        'v_ref' => $api_result['transactionid'] ?? "",
                        'token' => $api_result['metertoken'] ?? "",
                        'order_id' => $api_result['transactionid'] ?? "",
                    ];
                }
            }

            return [
                'api_status' => "error",
                'response' => "No APi provider available",
                'name' => "none",
                'api_status' => "fail",
                'ref' => $data['ref'],
            ];
        } catch (\Exception $e) {
            \Log::error($e);
            return [
                'api_status' => "error",
                'response' => $e->getMessage(),
                'name' => "none",
                'api_status' => "fail",
                'ref' => $data['ref'],
            ];
        }
    }

    public function verifyCustomer($data)
    {
        // External API logic
        $plan = Betsite::find($data['plan']);
        $provider = api_setting('betting_sel');
        try {
            // Ncwallet Provider
            if ($provider == 'ncwallet') {
                $slot = new Ncwallet();
                $payload = [
                    'betting_number' => $data['number'],
                    'betsite_id' => $plan->ncwallet,
                ];
                $api_result = $slot->validateBet($payload);
                if(isset($api_result['status']) && $api_result['status'] == "success" ){
                    return [
                        'name' => $api_result['betting_name'],
                        'api_status' => "success",
                        'response' => $api_result,
                        'message' => "Customer Details Validated Successfully",
                    ];
                } else {
                    return [
                        'name' => null,
                        'api_status' => "error",
                        'response' => $api_result,
                        'message' => "Unable to verify Customer Details",
                    ];
                }

            }
            // Clubkonnect
            else if ($provider == 'clubkonnect') {
                $vend = new Clubkonnect();
                $payload = [
                    "CustomerID" => $data["number"],
                    "BettingCompany" => $plan['clubkonnect'],
                ];
                $api_result = $vend->verifyBet($payload);
                if (isset($api_result['customer_name']) ) {
                    return [
                        'name' => $api_result['customer_name'],
                        'api_status' => "success",
                        'response' => $api_result,
                        'message' => "Customer Details Validated Successfully",
                    ];
                } else {
                    return [
                        'name' => null,
                        'api_status' => "error",
                        'response' => $api_result,
                        'message' => "Unable to verify Customer Details",
                    ];
                }
            }

            return [
                'api_status' => "error",
                'message' => "Unable to verify Customer",
                'name' => "none",
                'api_status' => "error",
            ];
        } catch (\Exception $e) {
            \Log::error($e);
            return [
                'name' => null,
                'api_status' => "error",
                'message' => "Unable to verify Customer Details",
                'response' => $e->getMessage(),
            ];
        }

    }
}
