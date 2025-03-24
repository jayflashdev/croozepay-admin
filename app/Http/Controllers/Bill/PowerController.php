<?php

namespace App\Http\Controllers\Bill;

use App\Http\Controllers\Controller;
use App\Models\Electricity;
use App\Models\User;
use App\Process\PowerProcess;
use App\Services\PowerService;
use Auth;
use Hash;
use Illuminate\Http\Request;

class PowerController extends Controller
{
    protected $powerService;

    public function __construct(PowerService $powerService)
    {
        $this->powerService = $powerService;
    }

    public function buypowerWeb(Request $request)
    {
        // Check user
        if (!Auth::check()) {
            return response()->json(['status' => 'error', 'message' => 'Unauthorized'], 401);
        }
        $user = User::where('id', Auth::id())->lockForUpdate()->first();
        // Validate the request payload (validate input fields)
        $validated = $request->validate([
            'amount' => 'required|numeric|min:1000',
            'meter_number' => 'required|numeric',
            'meter_type' => 'required|string|in:prepaid,postpaid',
            'disco' => 'required|numeric|exists:electricities,id',
            'customer_name' => 'nullable|string',
            'phone' => 'nullable|numeric',
            'bypass' => 'nullable|boolean',
            'pin' => 'required|numeric|digits:4',
            'saveBenef' => 'nullable|boolean',
        ]);

        if (!Hash::check($request->pin, $user->trxpin)) {
            return response()->json(['status' => 'error', 'message' => 'Transaction Pin is Incorrectrrect.'], 400);
        }
        // Pass validated power to the service
        $response = $this->powerService->processPower($user, $validated);

        // Return a success response
        return response()->json($response['body'], $response['http_code'] ?? 200);
    }

    /**
     * Handle the API top-up request.
     */
    public function buypowerApi(Request $request)
    {
        // API validation
        $validated = $request->validate([
            'amount' => 'required|numeric|min:1000',
            'meter_number' => 'required|numeric',
            'meter_type' => 'required|string|in:prepaid,postpaid',
            'disco' => 'required|numeric|exists:electricities,id',
            'customer_name' => 'nullable|string',
            'phone' => 'nullable|numeric',
            'bypass' => 'nullable|boolean',
            'ref_id' => ['nullable', 'string', 'max:45', 'alpha_num'],
        ]);

        $user = User::where('id', Auth::id())->lockForUpdate()->first();

        if (!$user) {
            return response()->json(['status' => 'error', 'message' => 'Unauthorized'], 401);
        }

        // Pass validated power to the service
        $response = $this->powerService->processPower($user, $validated,'API');

        // Return a success response
        return response()->json($response['body'], $response['http_code'] ?? 200);
    }

    // validate Meter
    public function verifyCustomer(Request $request)
    {
        // validate request
        $request->validate([
            'meter_number' => 'required|numeric',
            'meter_type' => 'required|string|in:prepaid,postpaid',
            'disco' => 'required|numeric|exists:electricities,id',
        ]);
        $data = [
            'number' => $request->meter_number,
            'type' => $request->meter_type,
            'disco' => $request->disco,
        ];
        // send to power service.
        $disco = Electricity::find($data['disco']);
        $pro = new PowerProcess();
        $res = $pro->verifyCustomer($data);
        if($res['api_status'] == 'success'){
            return response()->json([
                'status' => 'success',
                'message' => $res['message'],
                'customer_name' => $res['name'],
                'customer_number' => $request['number'],
                'customer_address' => $res['address'],
                'meter_type' => $request['type'],
                'disco_name' => $disco['name'],
            ]);
        }else{
            return response()->json([
                'status' => 'error',
                'message' => $res['message'],
                'customer_name' => null,
                'customer_address' => null
            ]);
        }
        return $res;
    }
}
