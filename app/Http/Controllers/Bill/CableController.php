<?php

namespace App\Http\Controllers\Bill;

use App\Http\Controllers\Controller;
use App\Models\Decoder;
use App\Models\User;
use App\Process\CableProcess;
use App\Services\CableService;
use Auth;
use Hash;
use Illuminate\Http\Request;

class CableController extends Controller
{
    protected $cableService;

    public function __construct(CableService $cableService)
    {
        $this->cableService = $cableService;
    }

    public function cableWeb(Request $request)
    {
        // Check user
        if (!Auth::check()) {
            return response()->json(['status' => 'error', 'message' => 'Unauthorized'], 401);
        }
        $user = User::where('id', Auth::id())->lockForUpdate()->first();
        // Validate the request payload (validate input fields)
        $validated = $request->validate([
            'number' => 'required|numeric',
            'cable' => 'required|numeric|exists:decoders,id',
            'cable_plan' => 'required|numeric|exists:cable_plans,id',
            'customer_name' => 'nullable|string',
            'phone' => 'nullable|numeric',
            'bypass' => 'nullable|boolean',
            'pin' => 'required|numeric|digits:4',
            'saveBenef' => 'nullable|boolean',
        ]);

        if (!Hash::check($request->pin, $user->trxpin)) {
            return response()->json(['status' => 'error', 'message' => 'Transaction Pin is Incorrect.'], 400);
        }
        // Pass validated data to the service
        $response = $this->cableService->processCable($user, $validated);

        // Return a success response
        return response()->json($response['body'], $response['http_code'] ?? 200);
    }

    /**
     * Handle the API top-up request.
     */
    public function cableApi(Request $request)
    {
        // API validation
        $validated = $request->validate([
            'number' => 'required|numeric|min:9',
            'cable' => 'required|numeric|exists:decoders,id',
            'cable_plan' => 'required|numeric|exists:cable_plans,id',
            'customer_name' => 'nullable|string|required',
            'phone' => 'nullable|numeric',
            'bypass' => 'nullable|boolean',
            'ref_id' => ['nullable', 'string', 'max:45', 'alpha_num'],
        ]);

        $user = User::where('id', Auth::id())->lockForUpdate()->first();

        if (!$user) {
            return response()->json(['status' => 'error', 'message' => 'Unauthorized'], 401);
        }

        // Pass validated data to the service
        $response = $this->cableService->processCable($user, $validated,'API');

        // Return a success response
        return response()->json($response['body'], $response['http_code'] ?? 200);
    }


    // validate Details
    public function verifyCustomer(Request $request)
    {
        // validate request
        $request->validate([
            'number' => 'required|numeric',
            'cable' => 'required|numeric|exists:decoders,id',
        ]);
        $data = [
            'number' => $request->number,
            'cable' => $request->cable,
        ];
        // send to power service.
        $cable = Decoder::find($data['cable']);
        $pro = new CableProcess();
        $res = $pro->verifyCustomer($data);
        if($res['api_status'] == 'success'){
            return response()->json([
                'status' => 'success',
                'message' => $res['message'],
                'customer_name' => $res['name'],
                'customer_package' => $res['plan'],
                'customer_number' => $request['number'],
                'plan_name' => $cable['name'],
            ]);
        }else{
            return response()->json([
                'status' => 'error',
                'message' => $res['message'],
                'customer_name' => null,
                'customer_package' => null
            ]);
        }
        return $res;
    }
}
