<?php

namespace App\Http\Controllers\Admin;

use App\User;
use App\Enums\UserType;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Mail;

class MessageController extends Controller
{
    public function professional()
    {
        $param['page_title'] = trans('main.Message');
        $param['page_description'] = trans('main.Management');
        $param['type'] = UserType::BUYER;
        $param['users'] = User::where('type', UserType::BUYER)->paginate(PAGINATION_SIZE);

        return view('admin.pages.message.index', $param);
    }

    public function general()
	{
        $param['page_title'] = trans('main.Message');
        $param['page_description'] = trans('main.Management');
        $param['type'] = UserType::SELLER;
        $param['users'] = User::where('type', UserType::SELLER)->paginate(PAGINATION_SIZE);

        return view('admin.pages.message.index', $param);
    }

    public function send($type, Request $request)
    {
        $emails = $request->input('user_emails');
        $emails = explode(',', $emails);

        if ($type == UserType::BUYER) {
            $emailName = 'email.professionalmsg_';
        } else {
            $emailName = 'email.usermessage_';
        }

        foreach ($emails as $email) {
            $param = ['email' => $email,
                'name' => 'keshav',
                'message1' => $request->input('allmessage'),
            ];
            $name = REPLY_NAME;

            Mail::send($emailName . \App::getLocale(), $param, function ($message) use ($email, $name) {
                $message->from(NOREPLY_EMAIL, REPLY_NAME);
                $message->to($email)
                    ->subject(trans('main.message_subject'));
            });
        }

        session()->flash('message', ['type' => 'success', 'msg' => trans('main.Contact Success')]);
        return redirect()->back();
    }
}
