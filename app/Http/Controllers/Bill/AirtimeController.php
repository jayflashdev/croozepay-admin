<?php

namespace App\Http\Controllers\Bill;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Services\AirtimeService;
use Auth;
use Hash;
use Illuminate\Http\Request;

class AirtimeController extends Controller
{
    protected $topupService;

    public function __construct(AirtimeService $topupService)
    {
        $this->topupService = $topupService;
    }

    /**
     * Handle the web top-up request.
     */
    public function topupWeb(Request $request)
    {
        // Check user
        if (!Auth::check()) {
            return response()->json(['status' => 'error', 'message' => 'Unauthorized'], 401);
        }
        $user = User::where('id', Auth::id())->lockForUpdate()->first();
        // Validate the request payload (validate input fields)
        $validated = $request->validate([
            'amount' => 'required|numeric|min:100',
            'phone' => 'required|digits:11|numeric',
            'network' => 'required|exists:networks,id',
            'pin' => 'required|digits:4|numeric',
            'saveBenef' => 'nullable|boolean',
            'bypass' => 'nullable|boolean',
        ]);

        if (!Hash::check($request->pin, $user->trxpin)) {
            return response()->json(['status' => 'error', 'message' => 'Transaction Pin is Incorrect.'], 400);
        }
        // Pass validated data to the service
        $response = $this->topupService->processTopup($user, $validated);

        // Return a success response
        return response()->json($response['body'], $response['http_code'] ?? 200);
    }

    /**
     * Handle the API top-up request.
     */
    public function topupApi(Request $request)
    {
        // API validation
        $validated = $request->validate([
            'network' => 'required|string',
            'phone' => 'required|string',
            'amount' => 'required|numeric',
            'plan_type' => 'required|string',
            'ref_id' => ['nullable', 'string', 'max:45', 'alpha_num'],
        ]);

        $user = Auth::user();

        if (!$user) {
            return response()->json(['status' => 'error', 'message' => 'Unauthorized'], 401);
        }

        // Pass validated data to the service
        $response = $this->topupService->processTopup($user, $validated, 'API');

        // Return a success response
        return response()->json($response['body'], $response['http_code'] ?? 200);
    }


}
