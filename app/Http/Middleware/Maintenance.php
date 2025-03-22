<?php

namespace App\Http\Middleware;

use Closure;

class Maintenance
{
    public function handle($request, Closure $next)
    {

        if (sys_setting('is_maintenance') == 1) {
            session(['mlink' => url()->current()]);

            return redirect(route('maintenance'));
        }

        return $next($request);
    }
}
