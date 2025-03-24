<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\AuthenticatedSessionController;
use App\Http\Controllers\Auth\PasswordController;
use App\Http\Controllers\Auth\RegisteredUserController;
use App\Http\Controllers\Auth\VerifyEmailController;
use App\Http\Controllers\ProfileController;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

Route::prefix('v1')->group(function () {
    Route::get('/health', function () {
        return response()->json(['status' => 'ok']);
    });
    // Auth
    Route::prefix('auth')->middleware('api.guest')->group(function () {
        Route::post('register', [RegisteredUserController::class, 'store'])->name('register');
        Route::post('login', [AuthenticatedSessionController::class, 'store']);
        // Forgot password
        Route::post('password/forgot', [PasswordController::class, 'forgotPassword']);
        Route::post('password/resend', [PasswordController::class, 'resendCode']);
        Route::post('password/confirm', [PasswordController::class, 'confirmCode']);
        Route::post('password/reset', [PasswordController::class, 'resetPassword']);
    });
    // email veritification
    Route::middleware('auth:sanctum')->group(function () {
        // Email verification
        Route::prefix('email')->controller(VerifyEmailController::class)->group(function () {
            Route::post('verify', 'verifyCode');
            Route::post('resend', 'resend');
        });
        // Logout
        Route::post('logout', [AuthenticatedSessionController::class, 'destroy']);
        // refresh token
        Route::post('/refresh', [AuthenticatedSessionController::class, 'refresh']);
    });
    // User Routes
    Route::middleware(['auth:sanctum'])->group(function () {
        // Profile management
        Route::get('/profile', [ProfileController::class, 'show']);
        Route::patch('/profile', [ProfileController::class, 'update']);
        Route::put('/profile/password', [ProfileController::class, 'updatePassword']);
        // update fcm token
        Route::put('/fcm-token', [ProfileController::class, 'updateFcmToken']);
        // delete account
        Route::delete('/profile', [ProfileController::class, 'deleteAccount']);
    });

    // Bills Service
    Route::middleware('auth:sanctum')->group(function(){
        Route::post('/airtime', [App\Http\Controllers\Bill\AirtimeController::class, 'topupApi'])->name('airtime.buy');
        Route::post('/data', [App\Http\Controllers\Bill\DataController::class, 'buydataApi'])->name('data.buy');
        Route::post('/electricity', [App\Http\Controllers\Bill\PowerController::class, 'buypowerApi'])->name('electricity.buy');
        Route::post('/cable', [App\Http\Controllers\Bill\CableController::class, 'cableApi'])->name('cable.buy');
        // Route::post('/education', [App\Http\Controllers\Bill\ExamController::class, 'examApi'])->name('education');
        Route::post('/betting', [App\Http\Controllers\Bill\BettingController::class, 'bettingApi'])->name('betting.buy');
    });
    Route::as('api.')->group(function(){
        // verify
        Route::get('/electricity/verify', [App\Http\Controllers\Bill\PowerController::class, 'verifyCustomer'])->name('electricity.verify');
        Route::get('/cable/verify', [App\Http\Controllers\Bill\CableController::class, 'verifyCustomer'])->name('cable.verify');
        Route::get('/betting/verify', [App\Http\Controllers\Bill\BettingController::class, 'verifyCustomer'])->name('betting.verify');
    });

    // Payments

    // Others
});
