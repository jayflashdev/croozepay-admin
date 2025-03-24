<?php

namespace App\Process;

use App\Models\Electricity;
use App\Utility\{AdexUtility, Clubkonnect, Easyaccess, MsorgUtility, Ncwallet, Vtpass};

class PowerProcess
{
    public function purchasePower($data)
    {
        // External API logic
        $disco = Electricity::find($data['disco']);
        $provider = api_setting('power_sel');

        try {
            if (in_array($provider, ['adex1', 'adex2', 'adex3', 'adex4', 'adex5', 'adex6'])) {
                $slot = new AdexUtility($provider);
                $payload = [
                    'bypass' => true,
                    'request-id' => $data['ref'],
                    'meter_number' => $data['number'],
                    'disco' => $disco->$provider,
                    'amount' => $data['amount'],
                    'meter_type' => $data['type'],
                    'request-id' => $data['ref'],
                ];
                $api_result = $slot->buyPower($payload);
                if (isset($api_result['status']) && $api_result['status'] == "success") {
                    return [
                        'name' => $provider,
                        'ref' => $data['ref'],
                        'api_status' => "success",
                        'response' => $api_result,
                        'amount' => $api_result['amount'] ?? $data['amount'],
                        'v_ref' => $api_result['transid'] ?? "",
                        'token' => $api_result['token'] ?? null,
                        'units' => $api_result['units'] ?? null,
                        'message' => $api_result['response'] ?? $api_result['message'],
                    ];
                } else {
                    return [
                        'ref' => $data['ref'],
                        'name' => $provider,
                        'api_status' => "fail",
                        'message' => $api_result['message'] ?? "",
                        'amount' => $api_result['amount'] ?? $data['amount'],
                        'response' => $api_result,
                        'token' => $api_result['token'] ?? "",
                        'units' => $api_result['units'] ?? "",
                        'v_ref' => $api_result['transid'] ?? "",
                    ];
                }
            }

            // Msorg Providers
            else if (in_array($provider, ['msorg1', 'msorg2', 'msorg3', 'msorg4', 'msorg5', 'msorg6'])) {
                $slot = new MsorgUtility($provider);
                if($data['type'] == 'prepaid'){
                    $data['type'] = 1;
                }else{
                    $data['type'] = 2;
                }
                $payload = [
                    'meter_number' => $data['number'],
                    'disco_name' => $disco->$provider,
                    'Customer_Phone' => $data['phone'],
                    'customer_name' => $data['name'],
                    'customer_address' => " ",
                    'amount' => $data['amount'],
                    'MeterType' => $data['type'],
                ];
                $api_result = $slot->buyPower($payload);
                if( (isset($api_result) || isset($api_result['status'])) && $api_result['Status'] == 'successful' || $api_result['status'] == 'successful' || $api_result['Status'] == 'processing' || $api_result['Status'] == 'success' || $api_result['status'] == 'success' ){
                    return [
                        'name' => $provider,
                        'ref' => $data['ref'],
                        'api_status' => "success",
                        'response' => $api_result,
                        'token' => $api_result['token'] ?? "",
                        'units' => $api_result['units'] ?? "",
                        'amount' => $api_result['plan_amount'] ?? $data['amount'],
                        'message' => $api_result['api_response'] ?? "",
                        'v_ref' => $api_result['id'] ?? "",
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
                    'bypass' => false,
                    'request-id' => $data['ref'],
                    'meter_number' => $data['number'],
                    'disco' => $disco->ncwallet,
                    'amount' => $data['amount'],
                    'meter_type' => $data['type'],
                ];
                $api_result = $slot->buyPower($payload);
                if(isset($api_result['status']) && $api_result['status'] == "success" || $api_result['status'] == "pending"){
                    return [
                        'name' => "ncwallet",
                        'ref' => $data['ref'],
                        'api_status' => "success",
                        'response' => $api_result,
                        'token' => $api_result['token'] ?? null,
                        'units' => $api_result['units'] ?? "",
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
                        'amount' => $api_result['amount'] ?? $data['amount'],
                    ];
                }
            }

            // Vtpass Provider
            else if ($provider == 'vtpass') {
                $slot = new Vtpass();
                $payload = [
                    'billersCode' => $data['number'],
                    'variation_code' => $data['type'],
                    'request_id' => $slot->generateReference(),
                    'serviceID' => $disco->vtpass,
                    'phone' => $data['phone'],
                    'amount' => $data['amount'],
                ];
                $api_result = $slot->purchasePlan($payload);
                $logFile = 'logs/vtpass_log.txt';
                $logMessage = json_encode($api_result, JSON_PRETTY_PRINT);
                file_put_contents($logFile, $logMessage, FILE_APPEND);
                if (isset($api_result) && $api_result['code'] == 000 && ($api_result['content']['transactions']['status'] == 'initiated' || $api_result['content']['transactions']['status'] == 'delivered')) {

                    $token = $api_result['purchased_code'] ?? null;
                    $units = $api_result['Units'] ?? null;
                    return [
                        'name' => "vtpass",
                        'ref' => $data['ref'],
                        'api_status' => "success",
                        'response' => $api_result ,
                        'token' => $token,
                        'units' => $units,
                        'amount' => $data['amount'],
                        'v_ref' => $api_result['requestId'] ?? "",
                    ];
                } else {
                    return [
                        'ref' => $data['ref'],
                        'name' => "vtpass",
                        'api_status' => "fail",
                        'token' => null,
                        'units' => null,
                        'response' => $api_result,
                    ];
                }
            }

            // Clubkonnect
            else if ($provider == 'clubkonnect') {
                $vend = new Clubkonnect();
                if($data['type'] == 'prepaid'){
                    $data['type'] = "01";
                }else{
                    $data['type'] = "02";
                }
                $payload = [
                    'ElectricCompany' => $disco->clubkonnect,
                    'MeterType' => $data['type'],
                    'MeterNo' => $data['number'],
                    'Amount' => $data['amount'],
                    'PhoneNo' => $data["phone"],
                    'CallBackURL' => route('api.callback.clubkonnect'),
                    "RequestID" => $data['ref'],
                ];

                $api_result = $vend->buyPower($payload);
                if (isset($api_result['status']) && $api_result['status'] == "ORDER_RECEIVED") {
                    return [
                        'name' => "clubkonnect",
                        'ref' => $data['ref'],
                        'api_status' => "success",
                        'response' => $api_result,
                        'message' => $api_result['message'] ?? '',
                        'amount' => $api_result['amount'] ?? $data['amount'],
                        'v_ref' => $api_result['transactionid'] ?? "",
                        'token' => $api_result['metertoken'] ?? "",
                        'units' => $api_result['units'] ?? "",
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

    public function verifyCustomer($data){
        $provider = api_setting('power_sel');
        $disco = Electricity::find($data['disco']);
        try {
            if (in_array($provider, ['adex1', 'adex2', 'adex3', 'adex4', 'adex5', 'adex6'])) {
                $slot = new AdexUtility($provider);

                $payload = [
                    'disco' => $disco->$provider,
                    'meter_number' => $data['number'],
                    'meter_type' => $data['type'],
                ];
                $api_result = $slot->validateMeter($payload);
                if (isset($api_result['status']) && $api_result['status'] == "success") {
                    return [
                        'name' => $api_result['name'],
                        'api_status' => "success",
                        'response' => $api_result,
                        'address' => $api_result['address'] ?? "",
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

            // Msorg Providers
            else if (in_array($provider, ['msorg1', 'msorg2', 'msorg3', 'msorg4', 'msorg5', 'msorg6'])) {
                $slot = new MsorgUtility($provider);
                $payload = [
                    'meternumber' => $data['number'],
                    'disconame' => $disco->name,
                    'mtype' => strtoupper($data['type']),
                ];
                $api_result = $slot->validateMeter($payload);
                if (isset($api_result['invalid']) && $api_result['invalid'] == false) {
                    return [
                        'name' => $api_result['name'],
                        'address' => $api_result['address'],
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

            // Ncwallet Provider
            else if ($provider == 'ncwallet') {
                $slot = new Ncwallet();
                $payload = [
                    'meter_number' => $data['number'],
                    'meter_id' => $disco->ncwallet,
                    'meter_type' => $data['type'],
                ];
                $api_result = $slot->validateMeter($payload);
                if(isset($api_result['status']) && $api_result['status'] == 'success'){
                    return [
                        'name' => $api_result['meter_name'],
                        'address' => $api_result['meter_address'] ?? "",
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

            // Vtpass Provider
            else if ($provider == 'vtpass') {
                $slot = new Vtpass();
                $payload = [
                    'billersCode' => $data['number'],
                    'type' => $data['type'],
                    'serviceID' => $disco->vtpass,
                ];
                $api_result = $slot->verifyMeter($payload);
                if (isset($api_result['code']) && $api_result['code'] == 000 ) {
                    return [
                        'Customer_Name' => $api_result['name'],
                        'api_status' => "success",
                        'response' => $api_result,
                        'address' => $api_result['Address'] ?? "",
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
                if($data['type'] == 'prepaid'){
                    $data['type'] = "01";
                }else{
                    $data['type'] = "02";
                }
                $payload = [
                    'ElectricCompany' => $disco->clubkonnect,
                    'meterNo' => $data['number'],
                ];

                $api_result = $vend->verifyPower($payload);
                if (isset($api_result['customer_name']) ) {
                    return [
                        'name' => $api_result['customer_name'],
                        'api_status' => "success",
                        'response' => $api_result,
                        'address' => $api_result['address'] ?? "",
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
                'response' => null,
                'name' => "Try Again",
                'message' => "Unable to verify Customer Details",
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
