<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use Response;

class InviteController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth')->only('invite');
    }

    public function invite(Request $request)
    {
        $senderName = auth()->user()->name;
        $data = $request->all();
        if (!isset($data['description']) || $data['description'] == "") {
            $description = trans('main.Hello, I visited http://i.weredy.com/ and its great, you should visit it and sign up.');
        } else {
            $description = $data['description'];
        }
        $name = $data['name'];
        $email = $data['email'];
        $info = [
            'reply_name' => REPLY_NAME,
            'reply_email' => NOREPLY_EMAIL,
            'email' => $email,
            'name' => $name,
            'subject' => trans('main.Invited you to join') . "Weredy",
        ];

        $param = [
            'reply_name' => REPLY_NAME,
            'reply_email' => NOREPLY_EMAIL,
            'sender' => $senderName,
            'email' => $email,
            'name' => $name,
            'subject' => trans('main.Invited you to join') . "Weredy",
            'description' => $description,
            'user' => auth()->user()
        ];
        $subject = trans('main.Invited you to join') . " Weredy";

        Mail::send('email.invite.' . \App::getLocale(), $param, function ($message) use ($email, $subject, $description, $name) {
            $message->from(NOREPLY_EMAIL, REPLY_NAME);
            $message->to($email)
                ->subject($subject);
        });

        $result['type'] = 'success';
        return Response::json($result, 200);
    }

    public function inviteSignUp($sender, $email, $accept, Request $request)
    {
        if ($accept == '1') {
            $subject = trans('main.Accept invitation');
            $action = trans('main.accepted');
        } else {
            $subject = trans('main.Reject invitaion');
            $action = trans('main.rejected');
        }
        
        $param = ['reply_name' => REPLY_NAME,
            'reply_email' => NOREPLY_EMAIL,
            'sender' => $sender,
            'email' => $email,
            'subject' => $subject,
            'action' => $action
        ];

        $emailTemplate = 'email.invite_accept' . '_' . \App::getLocale();
        Mail::send($emailTemplate, $param, function ($message) use ($email, $subject) {
            $message->from(NOREPLY_EMAIL, REPLY_NAME);
            $message->to(ADMIN_EMAIL)
                ->subject($subject);
        });

        if ($accept == '1') {
            return redirect('/signup');
        } else {
            return redirect('/');
        }
    }
}
