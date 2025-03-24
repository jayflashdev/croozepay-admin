<?php

namespace App\Process;

use App\Models\{DatacardPlan, DataPlan, Network};
use App\Utility\{AdexUtility, Clubkonnect, Easyaccess, MsorgUtility, Ncwallet, Vtpass};

class DataProcess
{
    public function purchaseData($data)
    {
        // External API logic
        $network = Network::find($data['network']);
        $plan = DataPlan::find($data['plan']);
        if ($network->id == 1) { // MTN
            $networkPrefix = "mtn";
        } elseif ($network->id == 2) { // GLO
            $networkPrefix = "glo";
        } elseif ($network->id == 3) { // Airtel
            $networkPrefix = "airtel";
        } else { // 9MOBILE
            $networkPrefix = "mob";
        }
        $planType = strtolower($plan->type);

        $provider = api_setting($networkPrefix . "_data_".$planType);
        try {
            // Adex Providers
            if (in_array($provider, ['adex1', 'adex2', 'adex3', 'adex4', 'adex5', 'adex6'])) {
                $slot = new AdexUtility($provider);
                $payload = [
                    'network' => $network->$provider,
                    'phone' => $data['phone'],
                    'data_plan' => $plan->$provider,
                    'bypass' => true,
                    'request-id' => $data['ref'],
                ];
                $api_result = $slot->buyData($payload);
                if (isset($api_result['status']) && $api_result['status'] == "success") {
                    return [
                        'name' => $provider,
                        'ref' => $data['ref'],
                        'api_status' => "success",
                        'response' => $api_result,
                        'amount' => $api_result['amount'] ?? "",
                        'v_ref' => $api_result['transid'] ?? "",
                        'message' => $api_result['response'] ?? $api_result['message'],
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
                    'network' => $network->$provider,
                    'mobile_number' => $data['phone'],
                    'plan' => $plan->$provider,
                    'Ported_number' => true
                ];
                $api_result = $slot->buyData($payload);
                if(isset($api_result['Status']) && $api_result['Status']== "successful"){
                // if ((isset($api_result['Status'])) && $api_result['Status'] == 'successful' || $api_result['status'] == 'successful' || $api_result['Status'] == 'success' || $api_result['status'] == 'success') {
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
                        'message' => $api_result['api_response'] ?? "",
                        'v_ref' => $api_result['id'] ?? "",
                        'response' => $api_result,
                    ];
                }
            }

            // Ncwallet Provider
            else if ($provider == 'ncwallet') {
                $slot = new Ncwallet();
                $payload = [
                    'network' => $network->ncwallet,
                    'phone' => $data['phone'],
                    'data_plan' => $plan->ncwallet,
                    'bypass' => true,
                    'request-id' => $data['ref'],
                ];
                $api_result = $slot->buyData($payload);
                if (isset($api_result['status']) && $api_result['status'] == "success") {
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
                        'amount' => $api_result['amount'] ?? "",
                    ];
                }
            }

            // Vtpass Provider
            else if ($provider == 'vtpass') {
                $slot = new Vtpass();
                $payload = [
                    'serviceID' => $network->vtpass,
                    'phone' => $data['phone'],
                    'billersCode' => $data['phone'],
                    'variation_code' => $plan->vtpass,
                ];
                $api_result = $slot->purchasePlan($payload);
                if (isset($api_result) && $api_result['code'] == 000 && ($api_result['content']['transactions']['status'] == 'initiated' || $api_result['content']['transactions']['status'] == 'failed' || $api_result['content']['transactions']['status'] == 'delivered')) {
                    return [
                        'name' => "vtpass",
                        'ref' => $data['ref'],
                        'api_status' => "success",
                        'message' =>  "",
                        'response' => $api_result ,
                        'amount' => $api_result['content']['transactions']['total_amount'] ?? "",
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
                $vendName = "clubkonnect";
                $payload = [
                    'MobileNetwork' => $network->clubkonnect,
                    'MobileNumber' => $data['phone'],
                    'CallBackURL' => "{{route('api.callback.clubkonnect')}}",
                    'DataPlan' => $plan->clubkonnect,
                    "RequestID" => $data['ref']
                ];
                $api_result = $vend->buyData($payload);
                if (isset($api_result['status']) && $api_result['status'] == "ORDER_RECEIVED") {
                    return [
                        'name' => "clubkonnect",
                        'ref' => $data['ref'],
                        'api_status' => "success",
                        'response' => $api_result,
                        'message' => $api_result['message'] ?? "",
                        'amount' => $api_result['amount'] ?? "",
                        'v_ref' => $api_result['transactionid'] ?? "",
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
                    'dataplan' => $plan->easyaccess,
                    'network' => $network->easyaccess,
                    'mobileno' => $data['phone'],
                    'client_reference' => $data['ref'],
                ];
                $api_result = $slot->purchaseData($payload);
                if (isset($api_result['success']) && $api_result['success'] == 'true') {
                    return [
                        'name' => "easyaccess",
                        'ref' => $data['ref'],
                        'api_status' => "success",
                        'response' => $api_result,
                        'message' => $api_result['true_response'] ?? "",
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
            // None
            return [
                'api_status' => "error",
                'response' => "No APi provider available",
                'name' => 'none',
                'api_status' => "fail",
                'ref' => $data['ref'],
            ];
        } catch (\Exception $e) {
            \Log::error($e);
            return [
                'api_status' => "error",
                'response' => $e->getMessage(),
                'name' => "none",
                'message' => "Data purchase failed.",
                'ref' => $data['ref'],
            ];
        }

    }

    // Datacard
    function printCard($data)
    {
        $plan = DatacardPlan::find($data['plan']);
        $network = $plan->network;
        if ($network->id == 1) { // MTN
            $provider = api_setting('mtn_datacard');
        } elseif ($network->id == 2) { // GLO
            $provider = api_setting('glo_datacard');
        } elseif ($network->id == 3) { // Airtel
            $provider = api_setting('airtel_datacard');
        } else {
            $provider = api_setting('mob_datacard');
        }

        try {
            // Adex website
            if (in_array($provider, ['adex1', 'adex2', 'adex3', 'adex4', 'adex5', 'adex6'])) {
                $slot = new AdexUtility($provider);
                $payload = [
                    'network' => $network->$provider,
                    'plan_type' => $plan->$provider,
                    'card_name' => $data['name'],
                    'quantity' => $data['quantity']
                ];
                $api_result = $slot->buyDatacard($payload);
                if (isset($api_result['status']) && $api_result['status'] == "success") {
                    return [
                        'name' => $provider,
                        'ref' => $data['ref'],
                        'api_status' => "success",
                        'response' => $api_result,
                        'amount' => $api_result['amount'] ?? "",
                        'v_ref' => $api_result['transid'] ?? "",
                        'pin' => $api_result['pin'] ?? '',
                        "serial" => $api_result['serial'] ?? '',
                        "load_pin" => $api_result['load_pin'] ?? '',
                        'check_balance' =>  $api_result["check_balance"] ?? '*312#',
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
                    'network' => $network->$provider,
                    'data_plan' => $plan->$provider,
                    'quantity' => $data['quantity'],
                    'name_on_card'  => $data['name']
                ];
                $api_result = $slot->buyDatacard($payload);
                if(isset($api_result['Status']) && $api_result['Status']== "successful"){
                // if ((isset($api_result['Status'])) && $api_result['Status'] == 'successful' || $api_result['status'] == 'successful' || $api_result['Status'] == 'success' || $api_result['status'] == 'success') {
                    return [
                        'name' => $provider,
                        'ref' => $data['ref'],
                        'api_status' => "success",
                        'response' => $api_result,
                        'amount' => $api_result['plan_amount'] ?? 0,
                        'v_ref' => $api_result['id'] ?? null,
                        'pin' => $api_result['pin'] ?? null,
                        "serial" => $api_result['serial'] ?? null,
                        "load_pin" => $api_result['load_pin'] ?? null,
                        'check_balance' =>  $api_result["check_balance"] ?? '*312#',
                    ];
                } else {
                    return [
                        'ref' => $data['ref'],
                        'amount' => $api_result['plan_amount'] ?? "",
                        'name' => $provider,
                        'api_status' => "error",
                        'message' => $api_result['api_response'] ?? "",
                        'v_ref' => $api_result['id'] ?? "",
                        'response' => $api_result,
                    ];
                }
            }

            // Ncwallet Provider
            else if ($provider == 'ncwallet') {
                $slot = new Ncwallet();
                $payload = [

                    'name' => $data['name'],
                    'quantity' => $data['quantity'],
                    'network' => $network->ncwallet,
                    'data_plan' => $plan->ncwallet,
                    'request-id' => $data['ref'],
                ];
                $api_result = $slot->buyDatacard($payload);
                if (isset($api_result['status']) && $api_result['status'] == "success") {
                    return [
                        'name' => "ncwallet",
                        'ref' => $data['ref'],
                        'api_status' => "success",
                        'response' => $api_result,
                        'message' => $api_result['api_response'] ?? $api_result['message'] ?? "",
                        'amount' => preg_replace('/[^0-9.]/', '', $api_result['amount_paid']) ?? $data['amount'] ?? 0,
                        'v_ref' => $api_result['ref_id'] ?? "",
                        'pin' => $api_result['pin'] ?? null,
                        "serial" => $api_result['serial'] ?? null,
                        "load_pin" => $api_result['load_pin'] ?? null,
                        'check_balance' =>  $api_result["check_balance"] ?? '*312#',
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
                'message' => "Datacard purchase failed",
                'ref' => $data['ref'],
            ];
        }

    }
}
