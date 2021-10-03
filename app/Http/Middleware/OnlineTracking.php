<?php

namespace App\Http\Middleware;

use Closure;

class OnlineTracking
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        if (auth()->check()) {
            $user = auth()->user();
            $user->last_activity = now();
            $user->save();
        }

        return $next($request);
    }
}
