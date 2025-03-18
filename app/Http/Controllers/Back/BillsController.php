<?php

namespace App\Http\Controllers\Back;

use App\Http\Controllers\Controller;
use App\Models\{
    Betsite,
    CablePlan, DatacardPlan,
    DataBundle, Decoder, Education, Electricity, Network
};
use App\Utility\{AdexUtility, Clubkonnect, Easyaccess, MsorgUtility, Ncwallet, Vtpass};
use Illuminate\Http\Request;

class BillsController extends Controller
{
    //
    public function api_selection(){
      return view('admin.bills.selection') ;
    }
    public function api_setting(){
        return view('admin.bills.setting');
    }
    public function api_website(){
        return view('admin.bills.websites');
    }

    // API Balances
    function apiBalance(Request $request, $type = ""){
        $adexKeys = ['adex1','adex2','adex3','adex4','adex5','adex6'];
        $msorgKeys = ['msorg1', 'msorg2', 'msorg3', 'msorg4', 'msorg5', 'msorg6'];
        try {
            // Check if type is Clubkonnect
            if ($type == 'clubkonnect') {
                $slot = new Clubkonnect();
                return $slot->getBalance();
            }

            // Check if type is VTPass
            if ($type == 'vtpass') {
                $slot = new Vtpass();
                return $slot->getBalance();
            }
            if ($type == 'easyaccess') {
                $slot = new Easyaccess();
                return $slot->getBalance();
            }

            // Handle Ncwallet
            if ($type == 'ncwallet') {
                $slot = new Ncwallet();
                return $slot->getUser();
            }

            // Check if type belongs to Adex
            if (in_array($type, $adexKeys)) {
                $utility = new AdexUtility($type);
                return $utility->getBalance($type);
            }
            if (in_array($type, $msorgKeys)) {
                $utility = new MsorgUtility($type);
                $res =  $utility->getUser($type);
                return [
                    'balance' => format_number($res['user']['Account_Balance'] )?? "0"
                ];
            }

            // Default fallback response for unknown types
            return response()->json([
                'balance' => '100'
            ]);

        } catch (\Exception $e) {
            // Error handling
            return response()->json([
                'error' => 'Unable to fetch balance: ' . $e->getMessage(),
                'balance' => '0'
            ], 500);
        }

    }
}
