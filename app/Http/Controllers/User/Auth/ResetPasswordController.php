<?php

namespace App\Http\Controllers\User\Auth;

use App\User;
use Illuminate\Http\Request;
use App\Models\PasswordReset;
use App\Http\Controllers\Controller;
use App\Providers\RouteServiceProvider;

class ResetPasswordController extends Controller
{
    /**
     * Where to redirect users after resetting their password.
     *
     * @var string
     */
    protected $redirectTo = RouteServiceProvider::HOME;

    public function __construct()
    {
        $this->middleware('guest');
    }

    public function showResetForm(Request $request, $token = null)
    {
        $passwordReset = PasswordReset::where('token', $token)->first();

        if (!$passwordReset) {
            return view('user.pages.auth.passwords.email', ['error' => trans('main.Token is invalid or expired.')]);
        }
        return view('user.pages.auth.passwords.reset')->with(
            ['token' => $token, 'email' => $request->email]
        );
    }

    public function resetPassword(Request $request)
    {
        $request->validate([
            'email' =>'required',
            'password' => 'required|confirmed',
        ]);

        $token = $request->input('token');

        $passwordReset = PasswordReset::where('token', $token)->first();
        if (!$passwordReset || $request->input('email') != $passwordReset->email) {
            session()->flash('alert', ['type' => 'danger', 'msg' => trans('main.Token is invalid or expired.')]);
            return redirect()->back();
        }

        $user = User::where('email', $request->input('email'))->first();
        $user->password = bcrypt($request->input('password'));
        $user->save();

        session()->flash('alert', ['type' => 'success', 'msg' => trans('main.Password has been reset successfully')]);
        return redirect()->route('user.auth.showLogin');
    }
}
