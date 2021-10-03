<?php

namespace App\Http\Controllers\User;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Mail;

class ContactUsController extends Controller
{
    public function showContactUs()
    {
        $requestOptions = [
            'main.contact.registration',
            'main.contact.profileViewing',
            'main.contact.Offices and address',
            'main.contact.Billing',
            'main.contact.post services',
            'main.contact.display services',
            'main.contact.reservationsAndPurchase',
            'main.contact.cancel booking',
            'main.contact.refundsAndFee',
            'main.contact.Customer Orders',
            'main.contact.payment',
            'main.contact.Balance Earnings',
            'main.contact.Articles',
            'main.contact.Comments and reviews',
            'main.contact.emailAndNotifications',
            'main.contact.others'
        ];

        return view('user.pages.contact-us', compact('requestOptions'));
    }

    public function sendContactRequest(Request $request)
    {
        $rules = [
            'email' => 'required|email',
            'name' => 'required',
            'request_option' => 'required',
            'message' => 'required',
        ];

        $messages = [
            'email.required' => trans('main.Enter your email'),
            'email.email' => trans('main.valid email'),
            'name.required' => trans('main.Enter your name'),
            'request_option.required' => trans('main.contact.supportRequest'),
            'message.required' => trans('main.Please fill message field !'),
        ];

        $this->validate($request, $rules, $messages);

        $param = [
            'email' => $request->input('email'),
            'name' => $request->input('name'),
            'message1' => $request->input('message'),
            'option' => $request->input('request_option'),
            'telephone' => $request->input('telephone'),
        ];

        $email = $request->input('email');
        $name = $request->input('name');
        $message1 = $request->input('message');

        $locale = \App::getLocale();
        Mail::send('email.contactus' . (($locale != 'en') ? '_' . $locale : ''), $param, function ($message) use ($email, $name, $message1, $request) {
            $message->from($email, $name);
            $message->to(REPLY_EMAIL)
                ->subject(trans('main.contact.mail.support') . ' ' . $request->input('option') . ' ' . trans('main.contact.mail.for') . ' ' . $name);
        });

        Mail::send('email.auto-response' . (($locale != 'en') ? '_' . $locale : ''), $param, function ($message) use ($email, $name, $message1, $request) {
            $message->from(NOREPLY_EMAIL, SITE_NAME . ' Support');
            $message->to($email, $name)
                ->subject('RE: ' . trans('main.contact.mail.support') . ' ' . $request->input('option') . ' ' . trans('main.contact.mail.for') . ' ' . $name);
        });

        session()->flash('alert', ['type' => 'success', 'msg' => trans('main.Contact Success')]);
        return redirect()->route('user.contact-us');
    }
    
}
