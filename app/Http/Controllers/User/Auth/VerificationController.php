<?php

namespace App\Http\Controllers\User\Auth;

use App\User;
use App\Models\EmailVerify;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Providers\RouteServiceProvider;
use Illuminate\Foundation\Auth\VerifiesEmails;

class VerificationController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Email Verification Controller
    |--------------------------------------------------------------------------
    |
    | This controller is responsible for handling email verification for any
    | user that recently registered with the application. Emails may also
    | be re-sent if the user didn't receive the original email message.
    |
    */

    use VerifiesEmails;

    /**
     * Where to redirect users after verification.
     *
     * @var string
     */
    protected $redirectTo = RouteServiceProvider::HOME;

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {

    }

    public function verifyEmail(Request $request, $token)
    {
        $verify = EmailVerify::where('token', $token)->first();
        if (!$verify) {
            $alert = [
                'type' => 'danger',
                'msg' => 'Your token is invalid'
            ];
            session()->flash('alert', $alert);

            return redirect()->route('user.auth.showLogin');
        }

        $user = User::where('email', $verify->email)->first();

        if (!$user) {
            $alert = [
                'type' => 'danger',
                'msg' => trans('main.User who has that email doesn\'t exist.')
            ];
        } else {
            $user->email_verified = 1;
            $user->save();

            $alert = [
                'type' => 'success',
                'msg' => trans('main.Your email verified successfully!'),
            ];
        }
        session()->flash('alert', $alert);

        return redirect()->route('user.auth.showLogin');
    }
}
