<?php

namespace App\Process;
use App\Utility\{AdexUtility, Clubkonnect, Easyaccess, MsorgUtility, Ncwallet, Vtpass};

class BulksmsProcess
{
    public function sendSms($data)
    {
        // External API logic
        $provider = api_setting('bulksms_sel');
        try {

            if (in_array($provider, ['adex1', 'adex2', 'adex3', 'adex4', 'adex5', 'adex6'])) {
                $slot = new AdexUtility($provider);
                $payload = [
                    "sender" =>$data['sender'],
                    "number" => $data['number'],
                    "message" => $data['message'],
                    'request-id' => $data['ref'],
                ];
                $api_result = $slot->sendSMS($payload);
                if (isset($api_result['status']) && $api_result['status'] == "success") {
                    return [
                        'name' => $provider,
                        'ref' => $data['ref'],
                        'api_status' => "success",
                        'response' => $api_result,
                        'amount' => $api_result['amount'] ?? 0,
                        'v_ref' => $api_result['transid'] ?? "",
                        'message' => $api_result['response'] ?? $api_result['message'],
                    ];
                } else {
                    return [
                        'ref' => $data['ref'],
                        'name' => $provider,
                        'api_status' => "fail",
                        'message' => $api_result['message'] ?? "",
                        'amount' => $api_result['amount'] ?? 0,
                        'response' => $api_result,
                        'v_ref' => $api_result['transid'] ?? "",
                    ];
                }
            }
            // Ncwallet Provider
            else if ($provider == 'ncwallet') {
                $slot = new Ncwallet();
                $payload = [
                    "sender" =>$data['sender'],
                    "number" => $data['number'],
                    "message" => $data['message'],
                    'request-id' => $data['ref'],
                ];
                return [
                    'ref' => $data['ref'],
                    'name' => "ncwallet",
                    'api_status' => "error",
                    'response' => "Bulsms not sent",
                    'amount' => 0,
                ];

                // $api_result = $slot->sendSms($payload);
                // if(isset($api_result['status']) && $api_result['status'] == "success" || $api_result['status'] == "pending"){
                //     return [
                //         'name' => "ncwallet",
                //         'ref' => $data['ref'],
                //         'api_status' => "success",
                //         'response' => $api_result,
                //         'message' => $api_result['api_response'] ?? $api_result['message'] ?? "",
                //         'amount' => preg_replace('/[^0-9.]/', '', $api_result['amount_paid']) ??  0,
                //         'v_ref' => $api_result['ref_id'] ?? "",
                //     ];
                // } else {
                //     return [
                //         'ref' => $data['ref'],
                //         'name' => "ncwallet",
                //         'api_status' => "error",
                //         'response' => $api_result,
                //         'amount' => $api_result['amount'] ?? 0,
                //     ];
                // }
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
}
