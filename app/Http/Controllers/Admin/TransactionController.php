<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\TransactionOfBooking;
use Illuminate\Http\Request;

class TransactionController extends Controller
{
    public function index()
    {
        $param['page_title'] = trans('main.Transaction');
        $param['page_description'] = trans('main.List');
        $param['transactions'] = TransactionOfBooking::orderBy('created_at', 'DESC')->paginate(PAGINATION_SIZE);

        return view('admin.pages.transaction.index', $param);
    }

    public function refund(Request $request)
    {
        if ($request->ajax()) {
            $id = $request->input('id');
            $with_fee = $request->input('with_fee');
            $transaction = TransactionOfBooking::find($id);

            //Refund Only Fee
            if ($transaction->service_id && $transaction->receiver_id == 0) {
                // create the reverse for a refund
                TransactionOfBooking::create([
                    'sender_id' => $transaction->receiver_id,
                    'receiver_id' => $transaction->sender_id,
                    'service_id' => $transaction->service_id,
                    'amount' => number_format($transaction->amount, 2),
                    'on_hold' => 0,
                    'book_id' => $transaction->book_id,
                ]);

                //Refund to balance
                $company = Company::find($transaction->sender_id);
                $company->balance += $transaction->amount;
                $company->save();

                // Also set the original transaction as not on hold
                $transaction->on_hold = 0;
                $transaction->refunded = 1;
                $transaction->save();
            }
            //Refund Service cost
            elseif ($transaction->service_id && $transaction->receiver_id > 0) {
                //Refund with fee
                if ($with_fee) {
                    $transaction_fee = TransactionOfBooking::where('book_id', $transaction->book_id)
                        ->where('sender_id', $transaction->receiver_id)
                        ->where('receiver_id', 0)
                        ->where('service_id', $transaction->service_id)
                        ->firstOrFail();
                    // refund fee
                    TransactionOfBooking::create([
                        'sender_id' => 0,
                        'receiver_id' => $transaction->receiver_id,
                        'service_id' => $transaction->service_id,
                        'amount' => number_format($transaction_fee->amount, 2),
                        'on_hold' => 0,
                        'book_id' => $transaction->book_id,
                    ]);

                    //Refund to balance
                    $company = Company::find($transaction->receiver_id);
                    $company->balance += $transaction_fee->amount;
                    $company->save();

                    // Also set the original fee transaction as not on hold
                    $transaction_fee->on_hold = 0;
                    $transaction_fee->refunded = 1;
                    $transaction_fee->save();
                }

                // create the reverse for a refund
                $reverseTransaction = TransactionOfBooking::create([
                    'sender_id' => $transaction->receiver_id,
                    'receiver_id' => $transaction->sender_id,
                    'service_id' => $transaction->service_id,
                    'amount' => number_format($transaction->amount, 2),
                    'on_hold' => 0,
                    'book_id' => $transaction->book_id,
                ]);

                //Refund to balance
                $sender = Company::find($transaction->sender_id);
                $sender->balance += $transaction->amount;
                $sender->save();

                $receiver = Company::find($transaction->receiver_id);
                $receiver->balance -= $transaction->amount;
                $receiver->save();

                // Also set the original transaction as not on hold
                $transaction->on_hold = 0;
                $transaction->refunded = 1;
                $transaction->save();

                //Set deleted by admin
                $book = Book::find($transaction->book_id);
                $book->status = 2;
                $book->deleted_by = 'admin';
                $book->save();

                $currentLang = $book->user->default_language;

                $param = [
                    'book' => $book,
                    'bookingTransaction' => $reverseTransaction,
                ];

                $info2User = [
                    'reply_name' => REPLY_NAME,
                    'reply_email' => NOREPLY_EMAIL,
                    'email' => $book->user->email,
                    'name' => $book->user->name,
                    'subject' => trans('main.email.booking.cancelByAdmin.buyer.subject',
                        ['site_name' => SITE_NAME]),
                ];

                $info2Professional = [
                    'reply_name' => REPLY_NAME,
                    'reply_email' => NOREPLY_EMAIL,
                    'email' => $book->service->company->email,
                    'name' => $book->service->name,
                    'subject' => trans('main.email.booking.cancelByAdmin.seller.subject',
                        ['site_name' => SITE_NAME], $book->user->default_language),
                ];

                \Mail::send('email.admin-cancel-book-buyer.' . $currentLang, $param, function ($message) use
                    ($info2User) {
                        $message->from($info2User['reply_email'], $info2User['reply_name']);
                        $message->to($info2User['email'], $info2User['name'])->subject($info2User['subject']);
                    });

                \Mail::send('email.admin-cancel-book-professional.' . $book->user->default_language, $param,
                    function ($message) use ($info2Professional) {
                        $message->from($info2Professional['reply_email'], $info2Professional['reply_name']);
                        $message->to($info2Professional['email'], $info2Professional['name'])
                            ->subject($info2Professional['subject']);
                    });
            }

            $alert['msg'] = trans('main.Refunded successfully');
            $alert['type'] = 'success';
            $request->session()->flash('alert', $alert);
            return response()->json([
                'result' => 'success',
            ]);
        } else {
            return redirect()->route('admin.transactions');
        }
    }
}
