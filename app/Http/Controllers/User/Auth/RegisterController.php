<?php

namespace App\Http\Controllers\User\Auth;

use App\Http\Controllers\Controller;
use App\Models\EmailVerify;
use App\Models\UserBalance;
use App\Models\UserDetail;
use App\Models\UserSetting;
use App\Providers\RouteServiceProvider;
use App\User;
use Illuminate\Auth\Events\Registered;
use Illuminate\Foundation\Auth\RegistersUsers;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Validator;

class RegisterController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Register Controller
    |--------------------------------------------------------------------------
     */

    use RegistersUsers;

    /**
     * Where to redirect users after registration.
     *
     * @var string
     */
    protected $redirectTo = RouteServiceProvider::HOME;

    public function __construct()
    {
        $this->middleware('guest');
    }

    protected function validator(array $data)
    {
        return Validator::make($data, [
            'name' => ['required', 'string', 'max:255'],
            'last_name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users'],
            'password' => ['required', 'string', 'confirmed', 'min:6'],
            'phone' => ['required', 'string', 'max:255', 'unique:users'],
            'type' => ['required'],
        ], [
            'email.unique' => trans('main.same-email-id'),
            'email.email' => trans('main.Enter your email'),
            'email.required' => trans('main.Enter your email'),
            'name.required' => trans('main.Enter your surname'),
            'last_name.required' => trans('main.Enter your name'),
            'phone.required' => trans('main.Enter your phone number including area code'),
            'phone.unique' => trans('main.same-phone-number'),
            'phone.phone' => trans('main.Enter your phone number including area code'),
            'password.required' => trans('main.Enter a password'),
            'password.confirmed' => trans('main.The passwords does not match'),
            'password_confirmation.required' => trans('main.Retype your password'),
            'type.required'  => trans('main.signup.become.seller.or.buyer.error.message'),
        ]);
    }

    /**
     * Create a new user instance after a valid registration.
     */
    protected function create(array $data)
    {
        $user = User::create([
            'name' => $data['name'],
            'last_name' => $data['last_name'],
            'email' => $data['email'],
            'password' => Hash::make($data['password']),
            'phone' => $data['phone'],
            'type' => $data['type'],
        ]);

        UserSetting::create([
            'user_id' => $user->id,
            'agree_privacy' => $data['agree_privacy'] ?? null,
            'agree_data' => $data['agree_data'] ?? null,
            'agree_update' => $data['agree_update'] ?? null,
        ]);

        UserDetail::create([
            'user_id' => $user->id,
        ]);

        /* UserBillingInfo::create([
        'user_id' => $user->id
        ]); */

        UserBalance::create([
            'user_id' => $user->id,
        ]);

        try {
            $this->sendAccountActivationEmail($user);
        } catch (\Exception $e) {
            print $e->getMessage();
            dd($e->getMessage());
        }

        return $user;
    }

    public function register(Request $request)
    {
        $this->validator($request->all())->validate();

        event(new Registered($user = $this->create($request->all())));

        $alert = [
            'msg' => trans('main.Check your email to verify your account'),
            'type' => 'success',
        ];
        session()->flash('alert', $alert);

        if ($response = $this->registered($request, $user)) {
            return $response;
        }

        return redirect()->route('user.auth.login');
    }

    public function registerProfessional(Request $request) {
        if ($request->input('email')) {
            session()->flash('professional_email', $request->input('email'));
        }

        if ($request->input('password')) {
            session()->flash('professional_password', $request->input('password'));
        }
        
        return redirect()->route('user.auth.showSignup');
    }

    /**
     * Show registration form
     */
    public function showRegistrationForm()
    {
        return view('user.pages.auth.register');
    }

    private function sendAccountActivationEmail($user)
    {
        $verifyToken = EmailVerify::updateOrCreate([
            'email' => $user->email,
        ], [
            'token' => str_random(32),
        ]);

        Mail::send(
            'email.active_' . app()->getLocale(),
            [
                'active_link' => route('user.verify-email', $verifyToken->token),
                'user' => $user,
            ], function ($message) use ($user) {
                $message->from(NOREPLY_EMAIL, REPLY_NAME)
                    ->subject(trans('main.Confirm registration on site'))
                    ->to($user->email, $user->name);
            }
        );
    }
}
