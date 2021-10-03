<?php

namespace App\Http\Controllers\User\Auth;

use App\User;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use App\Models\PasswordReset;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Mail;
use Illuminate\Foundation\Auth\SendsPasswordResetEmails;

class ForgotPasswordController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Password Reset Controller
    |--------------------------------------------------------------------------
    |
    | This controller is responsible for handling password reset emails and
    | includes a trait which assists in sending these notifications from
    | your application to your users. Feel free to explore this trait.
    |
    */

    use SendsPasswordResetEmails;

    /**
     * Display the form to request a password reset link.
     *
     * @return \Illuminate\View\View
     */
    public function showLinkRequestForm()
    {
        return view('user.pages.auth.passwords.email');
    }

    /**
     * Send a reset link to the given user.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Http\JsonResponse
     */
    public function sendResetLinkEmail(Request $request)
    {
        $request->validate([
            'email' => 'required|exists:users'
        ], [
            'exists' => trans('main.Email does not exist')
        ]);

        $email = $request->input('email');
        $user = User::where('email', $email)->first();
        $resetToken = PasswordReset::firstOrCreate(['email' => $email],
            ['token' => Str::random(60)]);
        $resetLink = route('user.forgot-password.showResetForm', $resetToken->token);

        Mail::send('email.reset_password.' . session('locale'), [
            'reset_link' => $resetLink
        ], function ($message) use ($user) {
            $message->from(env('MAIL_FROM_ADDRESS'), env('MAIL_FROM_NAME'))
                ->to($user->email, $user->full_name)
                ->subject(trans('passwords.reset request') . ' ' . env('APP_NAME'));
        });

        session()->flash('message', ['type' => 'success', 'msg' => trans('main.Password changes email has been sent')]);
        return redirect()->back();
    }
}
