<?php

namespace App\Http\Controllers\Bill;

use App\Http\Controllers\Controller;
use App\Models\Betsite;
use App\Models\User;
use App\Process\BetProcess;
use App\Services\BetService;
use Auth;
use Hash;
use Illuminate\Http\Request;

class BettingController extends Controller
{
    protected $betService;

    public function __construct(BetService $betService)
    {
        $this->betService = $betService;
    }

    public function bettingWeb(Request $request)
    {
        // Check user
        if (!Auth::check()) {
            return response()->json(['status' => 'error', 'message' => 'Unauthorized'], 401);
        }
        $user = User::where('id', Auth::id())->lockForUpdate()->first();
        // Validate the request payload (validate input fields)
        $validated = $request->validate([
            'amount' => 'required|numeric|min:100',
            'number' => 'required|numeric',
            'plan' => 'required|numeric|exists:betsites,id',
            'customer_name' => 'nullable|string',
            'phone' => 'nullable|numeric',
            'bypass' => 'nullable|boolean',
            'pin' => 'required|numeric|digits:4',
            'saveBenef' => 'nullable|boolean',
        ]);

        if (!Hash::check($request->pin, $user->trxpin)) {
            return response()->json(['status' => 'error', 'message' => 'Transaction Pin is Incorrect.'], 400);
        }
        // Pass validated bet to the service
        $response = $this->betService->processBet($user, $validated);

        // Return a success response
        return response()->json($response['body'], $response['http_code'] ?? 200);
    }

    /**
     * Handle the API top-up request.
     */
    public function bettingApi(Request $request)
    {
        // API validation
        $validated = $request->validate([
            'amount' => 'required|numeric|min:100',
            'number' => 'required|numeric',
            'plan' => 'required|numeric|exists:betsites,id',
            'customer_name' => 'nullable|string|required',
            'phone' => 'nullable|numeric',
            'bypass' => 'nullable|boolean',
            'ref_id' => ['nullable', 'string', 'max:45', 'alpha_num'],
        ]);

        $user = User::where('id', Auth::id())->lockForUpdate()->first();

        if (!$user) {
            return response()->json(['status' => 'error', 'message' => 'Unauthorized'], 401);
        }

        // Pass validated bet to the service
        $response = $this->betService->processBet($user, $validated,'API');

        // Return a success response
        return response()->json($response['body'], $response['http_code'] ?? 200);
    }


    // validate Details
    public function verifyCustomer(Request $request)
    {
        // validate request
        $request->validate([
            'number' => 'required|numeric',
            'plan' => 'required|numeric|exists:betsites,id',
        ]);
        $data = [
            'number' => $request->number,
            'plan' => $request->plan,
        ];
        // send to power service.
        $plan = Betsite::find($data['plan']);
        $pro = new BetProcess();
        $res = $pro->verifyCustomer($data);
        if($res['api_status'] == 'success'){
            return response()->json([
                'status' => 'success',
                'message' => $res['message'],
                'customer_name' => $res['name'],
                'customer_number' => $request['number'],
                'plan_name' => $plan['name'],
            ]);
        }else{
            return response()->json([
                'status' => 'error',
                'message' => $res['message'],
                'customer_name' => null,
            ]);
        }
        return $res;
    }
}
