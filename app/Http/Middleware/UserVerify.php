<?php

namespace App\Http\Middleware;

use Auth;
use Closure;
use Illuminate\Http\Request;

class UserVerify
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure(\Illuminate\Http\Request): (\Illuminate\Http\Response|\Illuminate\Http\RedirectResponse)  $next
     * @return \Illuminate\Http\Response|\Illuminate\Http\RedirectResponse
     */
    public function handle(Request $request, Closure $next)
    {
        $user = Auth::user();
        if($user->email_verify == 0 && sys_setting('verify_email') == 1){
            if($request->wantsJson()){
                return response()->json([
                    'status' => 'error',
                    'message' => 'Please Verify your Email to Continue',
                    'redirect' => route('verification.notice')
                ], 403);
            }
            return to_route('verification.notice')->withError('Please Verify your Email to Continue');
        }
        return $next($request);
    }
}
