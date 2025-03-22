<?php

namespace App\Http\Middleware;

use Auth;
use Closure;
use Illuminate\Http\Request;

class User
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Illuminate\Http\Response|\Illuminate\Http\RedirectResponse)  $next
     * @return \Illuminate\Http\Response|\Illuminate\Http\RedirectResponse
     */
    public function handle(Request $request, Closure $next)
    {
        if (! Auth::check()) {
            session(['link' => url()->current()]);

            if ($request->wantsJson()) {
                return response()->json([
                    'status' => 'error',
                    'message' => 'Unauthenticated',
                    'redirect' => route('login'),
                ], 401);
            }

            return redirect()->route('login');
        }

        $user = auth()->user();
        $redirectTo = $user->user_role == 'admin' || $user->user_role == 'staff' ? 'admin.login' : 'login';

        if ($user->blocked) {
            auth()->logout();
            $message = "Your account has been deleted or doesn't exist.";

            if ($request->wantsJson()) {
                return response()->json([
                    'status' => 'error',
                    'message' => $message,
                    'redirect' => route($redirectTo),
                ], 403);
            }

            return redirect()->route($redirectTo)->withEmodal($message)->withErrors($message);
        }

        if ($user->suspend) {
            auth()->logout();
            $message = 'Your account has been suspended. Contact admin for re-activation.';

            if ($request->wantsJson()) {
                return response()->json([
                    'status' => 'error',
                    'message' => $message,
                    'redirect' => route($redirectTo),
                ], 403);
            }

            return redirect()->route($redirectTo)->withEmodal($message)->withErrors($message);
        }

        if ($user->user_role == 'admin' || $user->user_role == 'user') {
            return $next($request);
        }

        if ($user->user_role == 'staff') {
            if ($request->wantsJson()) {
                return response()->json([
                    'status' => 'success',
                    'message' => 'Redirecting to admin index',
                    'redirect' => route('admin.index'),
                ]);
            }

            return redirect()->route('admin.index');
        }

        if ($request->wantsJson()) {
            return response()->json([
                'status' => 'success',
                'message' => 'Redirecting to index',
                'redirect' => route('index'),
            ]);
        }

        return redirect()->route('index');
    }
}
