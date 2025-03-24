<?php

namespace App\Process;

use App\Models\Education;
use App\Utility\{AdexUtility, Clubkonnect, Easyaccess, MsorgUtility, Ncwallet, Vtpass};

class ExamProcess
{
    public function purchaseExam($data)
    {
        // External API logic
        $exam = Education::find($data['exam']);
        if ($exam->code == strtoupper('waec')) {
            $provider = api_setting('waec_sel');
        } elseif ($exam->code == strtoupper('neco')) {
            $provider = api_setting('neco_sel');
        } elseif ($exam->code == strtoupper('nabteb')) { //
            $provider = api_setting('nabteb_sel');
        } else {
            $provider = null;
        }

        try {
            if (in_array($provider, ['adex1', 'adex2', 'adex3', 'adex4', 'adex5', 'adex6'])) {
                $slot = new AdexUtility($provider);
                $payload = [
                    'exam' => $exam->$provider,
                    'quantity' => $data['quantity'],
                    'request-id' => $data['ref'],
                ];
                $api_result = $slot->buyExamPins($payload);
                if (isset($api_result['status']) && $api_result['status'] == "success") {
                    return [
                        'name' => $provider,
                        'ref' => $data['ref'],
                        'api_status' => "success",
                        'response' => $api_result,
                        'amount' => $api_result['amount'] ?? 0,
                        'v_ref' => $api_result['transid'] ?? "",
                        'message' => $api_result['response'] ?? $api_result['message'],
                        'pin' => $api_result['pin'] ?? '',
                        'serial' => ""
                    ];
                } else {
                    return [
                        'ref' => $data['ref'],
                        'name' => $provider,
                        'api_status' => "fail",
                        'message' => $api_result['message'] ?? "",
                        'amount' => $api_result['amount'] ?? "",
                        'response' => $api_result,
                        'v_ref' => $api_result['transid'] ?? "",
                    ];
                }
            }

            // Msorg Providers
            else if (in_array($provider, ['msorg1', 'msorg2', 'msorg3', 'msorg4', 'msorg5', 'msorg6'])) {
                $slot = new MsorgUtility($provider);
                $payload = [
                    'exam_name' => strtoupper($exam->$provider),
                    'quantity' => $data['quantity'],
                ];
                $api_result = $slot->buyExamPins($payload);
                if( (isset($api_result['status'])) && $api_result['Status'] == 'successful' || $api_result['status'] == 'successful' || $api_result['Status'] == 'success' || $api_result['status'] == 'success' ){
                   return [
                        'name' => $provider,
                        'ref' => $data['ref'],
                        'api_status' => "success",
                        'response' => $api_result,
                        'amount' => $api_result['plan_amount'] ?? $data['amount'],
                        'v_ref' => $api_result['id'] ?? "",
                        'pin' => $api_result['pin'] ?? '',
                        'serial' => ""
                    ];
                } else {
                    return [
                        'ref' => $data['ref'],
                        'amount' => $api_result['plan_amount'] ?? "",
                        'name' => $provider,
                        'api_status' => "error",
                        'v_ref' => $api_result['id'] ?? "",
                        'response' => $api_result,
                    ];
                }
            }

            // Ncwallet Provider
            else if ($provider == 'ncwallet') {
                $slot = new Ncwallet();
                $payload = [
                    'exam' => $exam->ncwallet,
                    'quantity' => $data['quantity'],
                    'request-id' => $data['ref'],
                ];
                $api_result = $slot->buyExam($payload);
                if(isset($api_result['status']) && $api_result['status'] == "success" || $api_result['status'] == "pending"){
                    return [
                        'name' => "ncwallet",
                        'ref' => $data['ref'],
                        'api_status' => "success",
                        'response' => $api_result,
                        'pin' => $api_result['pin'] ?? $api_result['pins'] ?? '',
                        'serial' => "",
                        'message' => $api_result['api_response'] ?? $api_result['message'] ?? "",
                        'amount' => preg_replace('/[^0-9.]/', '', $api_result['amount_paid']) ?? $data['amount'] ?? 0,
                        'v_ref' => $api_result['ref_id'] ?? "",
                    ];
                } else {
                    return [
                        'ref' => $data['ref'],
                        'name' => "ncwallet",
                        'api_status' => "error",
                        'response' => $api_result,
                        'amount' => $api_result['amount'] ?? "",
                    ];
                }
            }

            // Vtpass Provider
            else if ($provider == 'vtpass') {
                $slot = new Vtpass();
                $payload = [
                    'serviceID' => strtolower($exam->code),
                    'phone' => $data['phone'],
                    'variation_code' => $exam->vtpass,
                    'quantity' => $data['quantity'],
                    'request_id' => $slot->generateReference('_EXAM')
                ];
                $api_result = $slot->purchasePlan($payload);
                if (isset($api_result) && $api_result['code'] == 000 && ($api_result['content']['transactions']['status'] == 'initiated' || $api_result['content']['transactions']['status'] == 'delivered')) {
                    return [
                        'name' => "vtpass",
                        'ref' => $data['ref'],
                        'api_status' => "success",
                        'message' =>$api_result['response_description'] ??  "",
                        'response' => $api_result ,
                        'pin' => $api_result['purchased_code'],
                        'serial' => "",
                        'amount' => $api_result['content']['transactions']['total_amount'] ?? $data['amount'],
                        'v_ref' => $api_result['requestId'] ?? "",
                    ];
                } else {
                    return [
                        'ref' => $data['ref'],
                        'name' => "vtpass",
                        'api_status' => "fail",
                        'response' => $api_result,
                    ];
                }
            }

            // Clubkonnect
            else if ($provider == 'clubkonnect') {
                $vend = new Clubkonnect();
                $payload = [
                    'ExamType' => ($exam->clubkonnect),
                    'PhoneNo' => $data['phone'],
                    'CallBackURL' => route('api.callback.clubkonnect'),
                    "RequestID" => $data['ref']
                ];
                if(strtoupper($exam->code) == strtoupper('JAMB')){
                    $api_result = $vend->purchaseJamb($payload);
                }else if(strtoupper($exam->code) == strtoupper('WAEC')){
                    $api_result = $vend->purchaseWaec($payload);
                } else{
                    return $res = [
                        'name' => "clubkonnect",
                        'ref' => $data['ref'],
                        'api_status' => "error",
                        'message' => "Invalid exam type selected",
                        'response' => "Invalid exam type selected",
                    ];
                }
                $logFile = 'logs/exam_webhook.txt';
                $logMessage = json_encode($api_result, JSON_PRETTY_PRINT);
                file_put_contents($logFile, $logMessage, FILE_APPEND);
                if (isset($api_result['status']) && $api_result['status'] == "ORDER_RECEIVED") {
                    return [
                        'name' => "clubkonnect",
                        'ref' => $data['ref'],
                        'api_status' => "success",
                        'response' => $api_result,
                        'message' => $api_result['message'] ?? '',
                        'amount' => $api_result['amount'] ?? $data['amount'],
                        'v_ref' => $api_result['transactionid'] ?? "",
                        'pin' => $api_result['pin'] ?? '',
                        'serial' => ""
                    ];
                } else {
                    return [
                        'name' => "clubkonnect",
                        'ref' => $data['ref'],
                        'api_status' => "error",
                        'response' => $api_result,
                        'amount' => $api_result['amount'] ?? "",
                        'v_ref' => $api_result['transactionid'] ?? "",
                    ];
                }
            }

            // Easyaccess Provider
            else if ($provider == 'easyaccess') {
                $slot = new Easyaccess();
                $payload = [
                    'no_of_pins' => $data['quantity'],
                    'client_reference' => $data['ref'],
                ];
                if($exam->code == strtoupper("waec") ){
                    $api_result = $slot->purchaseWaec($payload);
                }
                elseif($exam->code == strtoupper("neco") ) {
                    $api_result = $slot->purchaseNeco($payload);
                }
                elseif($exam->code == strtoupper("nabteb") ){
                    $api_result = $slot->purchaseNabteb($payload);
                };
                if (isset($api_result['success']) && $api_result['success'] == 'true') {
                    return [
                        'name' => "easyaccess",
                        'ref' => $data['ref'],
                        'api_status' => "success",
                        'response' => $api_result,
                        'pin' => $api_result['pin'],
                        'serial' => "",
                        'amount' => $api_result['amount'] ?? "",
                        'v_ref' => $api_result['reference_no'] ?? "",
                    ];
                } else {
                    return [
                        'name' => "easyaccess",
                        'ref' => $data['ref'],
                        'api_status' => "error",
                        'response' => $api_result,
                        'message' => $api_result['true_response'] ?? "",
                        'amount' => $api_result['amount'] ?? "",
                        'v_ref' => $api_result['reference_no'] ?? "",
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
}
