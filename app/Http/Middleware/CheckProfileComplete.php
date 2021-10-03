<?php

namespace App\Http\Middleware;

use App\Models\UserDetail;
use Closure;
use Illuminate\Support\Facades\Auth;

class CheckProfileComplete
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
        if (!isProfileCompleted()) {
            if ($request->is('complete-profile') || $request->is('complete-profile/*')) {
                $lastStep = $this->getRedirectToLastStep($request->route('step'));
                if ($lastStep != 'later') {
                    return redirect()->route('user.profile.wizard', $lastStep);
                }
            } else {
                $lastStep = $this->getRedirectToLastStep();
                return redirect()->route('user.profile.wizard', $lastStep);
            }
        }

        return $next($request);
    }

    public function getRedirectToLastStep($step = null)
    {
        $user = Auth::user();
        $wizardStepCompleted = $user->detail->profile_wizard_completed;

        if ($step) {
            if ($step == 'contact' && $wizardStepCompleted == UserDetail::NOTHING_COMPLETED) {
                return 'profile';
            }

            return 'later';
        } else {
            if ($wizardStepCompleted == UserDetail::NOTHING_COMPLETED) {
                return 'profile';
            }

            if ($wizardStepCompleted == UserDetail::PROFILE_COMPLETED) {
                return 'contact';
            }
        }
    }
}
