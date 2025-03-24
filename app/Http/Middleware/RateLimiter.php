<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Cache;
use Illuminate\Validation\ValidationException;

class RateLimiter
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Illuminate\Http\Response|\Illuminate\Http\RedirectResponse)  $next
     * @return \Illuminate\Http\Response|\Illuminate\Http\RedirectResponse
     */
    public function handle(Request $request, Closure $next)
    {
        $user = auth()->user(); // Assuming you have user authentication in place

        // Set a unique cache key for the user and the current URL
        $cacheKey = 'rate_limiter:'.$user->username;
        $limitInterval = 30;

        // Check if a cache entry for the key exists
        if (Cache::has($cacheKey)) {
            $lastRequestTime = Cache::get($cacheKey);
            $currentTime = Carbon::now();

            // Define the time interval you want to limit (e.g., 1 minute)
            $limitInterval = 30;

            // Calculate the time difference between the last request and the current time
            $timeDiff = $currentTime->diffInSeconds($lastRequestTime);

            // Check if the time difference is less than the limit interval
            if ($timeDiff < $limitInterval) {
                // Too many requests, throw an exception or handle it as needed
                throw ValidationException::withMessages([
                    'rate_limit' => 'Please wait a moment and try again.',
                ]);
            }
        }

        // Store the current time in the cache
        Cache::put($cacheKey, Carbon::now(), $limitInterval);

        return $next($request);
    }
}
