<?php

namespace App\Http\Middleware;

use App\Providers\RouteServiceProvider;
use Closure;
use Illuminate\Support\Facades\Auth;

class RedirectIfAuthenticated
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @param  string|null  $guard
     * @return mixed
     */
    public function handle($request, Closure $next, $guard = null)
    {
        // if user logged in who is correct owner of url, it redirects to login page per each guard
        if (Auth::guard($guard)->check()) {
            if ($guard == 'admin') {
                $redirectRoute = RouteServiceProvider::ADMIN_HOME;
            } else {
                $redirectRoute = RouteServiceProvider::HOME;
            }

            return redirect($redirectRoute);
        } else {
            // if user logged in who is not owner of url, it redirects to logged in user's page
            $activeGuard = $this->activeGuard();

            if ($activeGuard == 'web') {
                return redirect(RouteServiceProvider::HOME);
            } else if ($activeGuard == 'admin') {
                return redirect(RouteServiceProvider::ADMIN_HOME);
            }
        }

        return $next($request);
    }

    private function activeGuard(){

        foreach(array_keys(config('auth.guards')) as $guard){

            if(auth()->guard($guard)->check()) return $guard;

        }
        return null;
    }
}
