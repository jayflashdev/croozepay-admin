<?php

namespace App\Process;

use App\Models\Network;
use App\Models\RechargePlan;
use App\Utility\{AdexUtility, Clubkonnect, Easyaccess, MsorgUtility, Ncwallet, Vtpass};

class AirtimeProcess
{
    public function purchaseAirtime($data)
    {
        $network = Network::find($data['network']);
        // Fetch the provider setting based on the network
        if ($network->id == 1) { // MTN
            $provider = api_setting('mtn_airtime');
        } elseif ($network->id == 2) { // GLO
            $provider = api_setting('glo_airtime');
        } elseif ($network->id == 3) { // Airtel
            $provider = api_setting('airtel_airtime');
        } else {
            $provider = api_setting('mob_airtime');
        }
        try {
            if (in_array($provider, ['adex1', 'adex2', 'adex3', 'adex4', 'adex5', 'adex6'])) {
                $slot = new AdexUtility($provider);
                $payload = [
                    'network' => $network->$provider,
                    'phone' => $data['phone'],
                    'plan_type' => "VTU",
                    'bypass' => true,
                    'request-id' => $data['ref'],
                    'amount' => $data['amount'],
                ];
                $api_result = $slot->buyAirtime($payload);
                if (isset($api_result['status']) && $api_result['status'] == "success") {
                    return [
                        'name' => $provider,
                        'ref' => $data['ref'],
                        'api_status' => "success",
                        'response' => $api_result,
                        'amount' => $api_result['discount'] ?? "",
                        'v_ref' => $api_result['transid'] ?? "",
                        'message' => $api_result['message'] ?? "You have successfully purchased Airtime worth " . $data['amount'] . " to " . $data['phone'],
                    ];
                } else {
                    return [
                        'ref' => $data['ref'],
                        'name' => $provider,
                        'api_status' => "fail",
                        'message' => $api_result['response'] ?? "",
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
                    'airtime_type' => 'VTU',
                    'Ported_number' => true,
                    'amount' => $data['amount'],
                ];
                $api_result = $slot->buyAirtime($payload);
                if ((isset($api_result['Status'])) && $api_result['Status'] == 'successful' || $api_result['status'] == 'successful' || $api_result['Status'] == 'success' || $api_result['status'] == 'success') {
                    return [
                        'name' => $provider,
                        'ref' => $data['ref'],
                        'api_status' => "success",
                        'response' => $api_result,
                        'amount' => $api_result['paid_amount'] ?? "",
                        'message' => $api_result['response'] ?? "You have successfully purchased Airtime worth " . $data['amount'] . " to " . $data['phone'],
                        'v_ref' => $api_result['id'] ?? "",
                    ];
                } else {
                    return [
                        'ref' => $data['ref'],
                        'amount' => $api_result['paid_amount'] ?? "",
                        'name' => $provider,
                        'api_status' => "error",
                        'message' => $api_result['response'] ?? "",
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
                    'plan_type' => "VTU",
                    'bypass' => true,
                    'request-id' => $data['ref'],
                    'amount' => $data['amount'],
                ];
                $api_result = $slot->buyAirtime($payload);
                if (isset($api_result['status']) && $api_result['status'] == "success") {
                    return [
                        'name' => "ncwallet",
                        'ref' => $data['ref'],
                        'api_status' => "success",
                        'response' => $api_result,
                        'message' => $api_result['message'] ?? $api_result['msg'] ?? "You have successfully purchased Airtime worth " . $data['amount'] . " to " . $data['phone'],
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
                    'amount' => $data['amount'],
                ];
                $api_result = $slot->purchaseAirtime($payload);
                if (isset($api_result) && $api_result['code'] == 000 && ($api_result['content']['transactions']['status'] == 'initiated' || $api_result['content']['transactions']['status'] == 'failed' || $api_result['content']['transactions']['status'] == 'delivered')) {
                    return [
                        'name' => "vtpass",
                        'ref' => $data['ref'],
                        'api_status' => "success",
                        'message' => "You have successfully purchased Airtime worth " . $data['amount'] . " to " . $data['phone'],
                        'response' => $api_result ?? "",
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
                    'MobileNetwork' => $network->clubkonnect,  // Assuming the network has a 'clubkonnect' field
                    'MobileNumber' => $data['phone'],
                    'CallBackURL' => "{{route('api.callback.clubkonnect')}}",
                    "RequestID" => $data['ref'],
                    "Amount" => $data['amount']
                ];
                $api_result = $vend->buyAirtime($payload);
                if (isset($api_result['status']) && $api_result['status'] == "ORDER_RECEIVED") {
                    return [
                        'name' => "clubkonnect",
                        'ref' => $data['ref'],
                        'api_status' => "success",
                        'response' => $api_result,
                        'message' => $api_result['message'] ?? "You have successfully purchased Airtime worth " . $data['amount'] . " to " . $data['phone'],
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
                    'network' => $network->easyaccess,  // Assuming the network has an 'easyaccess' field
                    'phone' => $data['phone'],
                    'amount' => $data['amount'],
                ];
                $payload = [
                    'network' => $network->easyaccess,
                    'mobileno' => $data['phone'],
                    'airtime_type' => '001',
                    'client_reference' => $data['ref'],
                    'amount' => $data['amount'],
                ];
                $api_result = $slot->purchaseAirtime($payload);
                if (isset($api_result['success']) && $api_result['success'] == 'true') {
                    return [
                        'name' => "easyaccess",
                        'ref' => $data['ref'],
                        'api_status' => "success",
                        'response' => $api_result,
                        'message' => $api_result['true_response'] ?? "You have successfully purchased Airtime worth " . $data['amount'] . " to " . $data['phone'],
                        'amount' => $api_result['amountcharged'] ?? "",
                        'v_ref' => $api_result['reference_no'] ?? "",
                    ];
                } else {
                    return [
                        'name' => "easyaccess",
                        'ref' => $data['ref'],
                        'api_status' => "error",
                        'response' => $api_result,
                        'message' => $api_result['true_response'] ?? "",
                        'amount' => $api_result['amountcharged'] ?? "",
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
            return [
                'api_status' => "error",
                'response' => $e->getMessage(),
                'name' => "none",
                'api_status' => "fail",
                'ref' => $data['ref'],
            ];
        }
    }

    function printCard($data)
    {
        $plan = RechargePlan::find($data['plan']);
        $network = $plan->network;
        if ($network->id == 1) { // MTN
            $provider = api_setting('mtn_rechargecard');
        } elseif ($network->id == 2) { // GLO
            $provider = api_setting('glo_rechargecard');
        } elseif ($network->id == 3) { // Airtel
            $provider = api_setting('airtel_rechargecard');
        } else {
            $provider = api_setting('mob_rechargecard');
        }

        try {
            if (in_array($provider, ['adex1', 'adex2', 'adex3', 'adex4', 'adex5', 'adex6'])) {
                $slot = new AdexUtility($provider);
                $payload = [
                    'network' => $network->$provider,
                    'plan_type' => $plan->$provider,
                    'card_name' => $data['name'],
                    'quantity' => $data['quantity']
                ];
                $api_result = $slot->buyAirtimecard($payload);
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
                        'check_balance' =>  $api_result["check_balance"] ?? '*310#',
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
                $api_result = $slot->buyGeneratePins($payload);
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
                    'name_om_card' => $data['name'],
                    'quantity' => $data['quantity'],
                    'network' => $network->ncwallet,
                    'amount' => $plan->value,
                    'request-id' => $data['ref'],
                ];
                $api_result = $slot->buyAirtimecard($payload);
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
            else if($provider == 'clubkonnect'){
                $slot = new Clubkonnect();

                $payload = [
                    'MobileNetwork' => $network->clubkonnect,
                    'Value' => $plan->value,
                    'Quantity' => $data['quantity'],
                    'CallBackURL' => route('api.callback.clubkonnect'),
                    "RequestID" => $data['ref']
                ];

                $api_result = $slot->printCard($payload);
                $logFile = 'logs/clukonnect-response.txt';
                $logMessage = json_encode($api_result, JSON_PRETTY_PRINT);
                file_put_contents($logFile, $logMessage, FILE_APPEND);
                if(isset($api_result['status']) && $api_result['status'] == "ORDER_RECEIVED"){
                    return $res = [
                        'name' => "clubkonnect",
                        'ref' => $data['ref'],
                        'api_status' => "success",
                        'response' => $api_result,
                        'amount' => $api_result['amount'] ?? "",
                        'load_pin' => "*311*PIN#",
                        'pin' => $api_result['TXN_EPIN']['0']['pin'] ?? "",
                        'serial' => $api_result['TXN_EPIN']['0']['sno'] ?? "",
                        'check_balance' => "*310#"
                    ];
                }else if(isset($api_result['status']) && $api_result['status'] !== "ORDER_RECEIVED"){
                    return $res = [
                        'name' => "clubkonnect",
                        'ref' => $data['ref'],
                        'api_status' => "error",
                        'response' => $api_result,
                        'amount' => $api_result['amount'] ?? "",
                    ];
                }else{
                    return $res = [
                        'name' => "clubkonnect",
                        'ref' => $data['ref'],
                        'api_status' => "process",
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
                'message' => "Recharge card purchase failed",
                'ref' => $data['ref'],
            ];
        }

    }
}
