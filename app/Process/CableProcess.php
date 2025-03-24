<?php

namespace App\Process;

use App\Models\CablePlan;
use App\Models\Decoder;
use App\Utility\{AdexUtility, Clubkonnect, Easyaccess, MsorgUtility, Ncwallet, Vtpass};

class CableProcess
{
    public function purchaseCable($data)
    {
        // External API logic
        $cable = Decoder::find($data['cable']);
        $plan = CablePlan::find($data['plan']);
        if ($cable->id == 1) { // DSTV
            $provider = api_setting('dstv_sel');
        } elseif ($cable->id == 2) { // GOTV
            $provider = api_setting('gotv_sel');
        } elseif ($cable->id == 3) { // STartimes
            $provider = api_setting('startime_sel');
        } else {
            $provider = api_setting('dstv_sel');
        }

        try {
            if (in_array($provider, ['adex1', 'adex2', 'adex3', 'adex4', 'adex5', 'adex6'])) {
                $slot = new AdexUtility($provider);
                $payload = [
                    'cable' => $cable->$provider,
                    'iuc' => $data['number'],
                    'cable_plan' => $plan->$provider,
                    'bypass' => true,
                    'request-id' => $data['ref'],
                ];
                $api_result = $slot->buyCable($payload);
                if (isset($api_result['status']) && $api_result['status'] == "success") {
                    return [
                        'name' => $provider,
                        'ref' => $data['ref'],
                        'api_status' => "success",
                        'response' => $api_result,
                        'amount' => $api_result['amount'] ?? $data['amount'],
                        'v_ref' => $api_result['transid'] ?? "",
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
                        'v_ref' => $api_result['transid'] ?? "",
                    ];
                }
            }

            // Msorg Providers
            else if (in_array($provider, ['msorg1', 'msorg2', 'msorg3', 'msorg4', 'msorg5', 'msorg6'])) {
                $slot = new MsorgUtility($provider);
                $payload = [
                    'cablename' => $cable->$provider,
                    'smart_card_number' => $data['number'],
                    'cableplan' => $plan->$provider,
                    'customer_name' => $data['name']
                ];
                $api_result = $slot->buyCablesub($payload);
                if( (isset($api_result) || isset($api_result['status'])) && $api_result['Status'] == 'successful' || $api_result['status'] == 'successful' || $api_result['Status'] == 'processing' || $api_result['Status'] == 'success' || $api_result['status'] == 'success' ){
                    return [
                        'name' => $provider,
                        'ref' => $data['ref'],
                        'api_status' => "success",
                        'response' => $api_result,
                        'amount' => $api_result['plan_amount'] ?? $plan->price,
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
                    'cable' => $cable->ncwallet,
                    'iuc' => $data['number'],
                    'cable_plan' => $plan->ncwallet,
                    'bypass' => false,
                    'request-id' => $data['ref'],
                ];
                $api_result = $slot->buyCablesub($payload);
                if(isset($api_result['status']) && $api_result['status'] == "success" || $api_result['status'] == "pending"){
                    return [
                        'name' => "ncwallet",
                        'ref' => $data['ref'],
                        'api_status' => "success",
                        'response' => $api_result,
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
                    'serviceID' => $cable->vtpass,
                    'billersCode' => $data['number'],
                    'variation_code' => $plan->vtpass,
                    'phone' =>  $data['phone'],
                    'request_id' => $slot->generateReference('_CABLE')
                ];
                $api_result = $slot->purchasePlan($payload);
                if (isset($api_result) && $api_result['code'] == 000 && ($api_result['content']['transactions']['status'] == 'initiated' || $api_result['content']['transactions']['status'] == 'delivered')) {
                    return [
                        'name' => "vtpass",
                        'ref' => $data['ref'],
                        'api_status' => "success",
                        'message' =>$api_result['response_description'] ??  "",
                        'response' => $api_result ,
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
                    'CableTV' => ($cable->clubkonnect),
                    'SmartCardNo' => $data['number'],
                    'Package' => $plan->clubkonnect,
                    'PhoneNo' => $data['phone'],
                    'CallBackURL' => route('api.callback.clubkonnect'),
                    "RequestID" => $data['ref'],
                ];
                $api_result = $vend->purchaseCable($payload);
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
                    ];
                }
            }

            // Easyaccess Provider
            else if ($provider == 'easyaccess') {
                $slot = new Easyaccess();
                $payload = [
                    'cableplan' => $plan->easyaccess,
                    'cable' => $cable->easyaccess,
                    'number' => $data['number'],
                    'mobileno' => $data['phone'],
                    'client_reference' => $data['ref'],
                ];
                $api_result = $slot->purchaseCablePlan($payload);
                if (isset($api_result['success']) && $api_result['success'] == 'true') {
                    return [
                        'name' => "easyaccess",
                        'ref' => $data['ref'],
                        'api_status' => "success",
                        'response' => $api_result,
                        'message' => $api_result['true_response'] ?? "",
                        'amount' => $api_result['amount'] ?? $data['amount'],
                        'v_ref' => $api_result['reference_no'] ?? "",
                    ];
                } else {
                    return [
                        'name' => "easyaccess",
                        'ref' => $data['ref'],
                        'api_status' => "error",
                        'response' => $api_result,
                        'message' => $api_result['true_response'] ?? "",
                        'amount' => $api_result['amount'] ?? $data['amount'],
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

    // validation
    public function verifyCustomer($data){
        $cable = Decoder::find($data['cable']);
        if ($cable->id == 1) { // DSTV
            $provider = api_setting('dstv_sel');
        } elseif ($cable->id == 2) { // GOTV
            $provider = api_setting('gotv_sel');
        } elseif ($cable->id == 3) { // STartimes
            $provider = api_setting('startime_sel');
        } else {
            $provider = api_setting('dstv_sel');
        }
        try {
            if (in_array($provider, ['adex1', 'adex2', 'adex3', 'adex4', 'adex5', 'adex6'])) {
                $slot = new AdexUtility($provider);

                $payload = [
                    'cable' => $cable->$provider,
                    'iuc' => $data['number'],
                ];
                $api_result = $slot->validateCable($payload);
                if (isset($api_result['status']) && $api_result['status'] == "success") {
                    return [
                        'name' => $api_result['name'],
                        'api_status' => "success",
                        'response' => $api_result,
                        'plan' => $api_result['address'] ?? "",
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
                    'smart_card_number' => $data['number'],
                    'cablename' => strtoupper($cable->name),
                ];
                $api_result = $slot->validateCable($payload);
                if (isset($api_result['invalid']) && $api_result['invalid'] == false) {
                    return [
                        'name' => $api_result['name'],
                        'plan' => $api_result['plan'] ?? '',
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
                    'cable_number' => $data['number'],
                    'cable_id' => $cable->ncwallet,
                ];
                $api_result = $slot->validateCable($payload);
                if(isset($api_result['status']) && $api_result['status'] == 'success'){
                    return [
                        'name' => $api_result['cable_name'],
                        'plan' => $api_result['cable_plan'] ?? "",
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
                    'serviceID' => $cable->vtpass,
                ];
                $api_result = $slot->verifyMeter($payload);
                if (isset($api_result['code']) && $api_result['code'] == 000 ) {
                    return [
                        'Customer_Name' => $api_result['name'],
                        'api_status' => "success",
                        'response' => $api_result,
                        'plan' => $api_result['Current_Bouquet'] ?? "",
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
                    'CableTV' => $cable->clubkonnect,
                    'SmartCardNo' => $data['number'],
                ];

                $api_result = $vend->verifyCable($payload);
                if (isset($api_result['customer_name']) ) {
                    return [
                        'name' => $api_result['customer_name'],
                        'api_status' => "success",
                        'response' => $api_result,
                        'plan' => $api_result['plan'] ?? "",
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
            else {
                $vend = new Clubkonnect();

                $payload = [
                    'CableTV' => $cable->clubkonnect,
                    'SmartCardNo' => $data['number'],
                ];

                $api_result = $vend->verifyCable($payload);
                if (isset($api_result['customer_name']) ) {
                    return [
                        'name' => $api_result['customer_name'],
                        'api_status' => "success",
                        'response' => $api_result,
                        'plan' => $api_result['plan'] ?? "",
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
