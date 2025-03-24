<?php

namespace App\Http\Controllers\Bill;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Services\ExamService;
use Auth;
use Hash;
use Illuminate\Http\Request;

class ExamController extends Controller
{
    protected $examService;

    public function __construct(ExamService $examService)
    {
        $this->examService = $examService;
    }

    public function examWeb(Request $request)
    {
        // Check user
        if (!Auth::check()) {
            return response()->json(['status' => 'error', 'message' => 'Unauthorized'], 401);
        }
        $user = User::where('id', Auth::id())->lockForUpdate()->first();
        // Validate the request payload (validate input fields)
        $validated = $request->validate([
            'phone' => 'nullable|numeric',
            'quantity' => 'required|numeric|min:1',
            'exam' => 'required|numeric|exists:education,id',
            'pin' => 'required|numeric|digits:4',
        ]);

        if (!Hash::check($request->pin, $user->trxpin)) {
            return response()->json(['status' => 'error', 'message' => 'Transaction Pin is Incorrectrrect.'], 400);
        }
        // Pass validated data to the service
        $response = $this->examService->processExam($user, $validated);

        // Return a success response
        return response()->json($response['body'], $response['http_code'] ?? 200);
    }

    /**
     * Handle the API top-up request.
     */
    public function examApi(Request $request)
    {
        // API validation
        $validated = $request->validate([
            'phone' => 'nullable|numeric',
            'quantity' => 'required|numeric|min:1',
            'exam' => 'required|numeric|exists:education,id',
            'ref_id' => ['nullable', 'string', 'max:45', 'alpha_num'],
        ]);

        $user = User::where('id', Auth::id())->lockForUpdate()->first();

        if (!$user) {
            return response()->json(['status' => 'error', 'message' => 'Unauthorized'], 401);
        }

        // Pass validated data to the service
        $response = $this->examService->processExam($user, $validated,'API');

        // Return a success response
        return response()->json($response['body'], $response['http_code'] ?? 200);
    }

}
