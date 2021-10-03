<?php

namespace App\Http\Controllers;

use App\Models\Book;
use App\Models\Review;
use App\Models\UserBalance;
use App\Models\RequestModification;
use App\Models\TransactionOfCredit;
use App\Models\TransactionOfBooking;
use Illuminate\Support\Facades\Mail;
use App\Models\RequestExtendDeliveryDate;

class TestController extends Controller
{

    public function test()
    {
        // past 1 day ~ 4 days after book date for offline service
        $offlineBooks = Book::with('user')
            ->whereNull('delivery_date')
            ->whereNull('review_id')
            ->whereNull('deleted_by')
            ->whereRaw('(unix_timestamp(book_date)+86400)<unix_timestamp()')
            ->whereRaw('(unix_timestamp(book_date)+345600)>unix_timestamp()')
            ->get();

        // past 1 day ~ 4 days after book date for online service
        $onlineBooks = Book::with('user')
            ->whereNotNull('delivery_date')
            ->where('status', Book::STATUS_PROVIDED)
            ->whereNull('review_id')
            ->whereNull('deleted_by')
            ->whereRaw('(unix_timestamp(accepted_date)+86400)<unix_timestamp()')
            ->whereRaw('(unix_timestamp(accepted_date)+345600)>unix_timestamp()')
            ->get();

        $books = $offlineBooks->merge($onlineBooks);

        dd($books);
    }

    public function requestConfirmOnline() {
        $books = Book::whereNotNull('delivery_date')
        ->where('status', Book::STATUS_PENDING)
        ->where('delivery_date', '<', date('Y-m-d H:i:s', time()))
        ->where('notify_status', Book::NOTIFY_STATUS_NONE)
        ->get();
    
        $books->each(function ($item, $key) {
            $defaultLang = $item->user->default_language;
            Mail::send('email.online-service-completed-buyer-need-confirm.' . $defaultLang,
                ['book' => $item],
                function ($message) use ($item, $defaultLang) {
                    $message->from(NOREPLY_EMAIL, REPLY_NAME);
                    $message->to($item->user->email, $item->user->name)
                        ->subject(trans('main.email.order.completed.buyer.need.confirm.object', [], $defaultLang));
                }
            );

            $item->notify_status = Book::NOTIFY_STATUS_REQUEST_CONFIRM;
            $item->save();
        });
        dd($books);
    }

    public function autoAcceptOnlineDelivery() {
        $books = Book::whereNotNull('delivery_date')
            ->where('status', Book::STATUS_PENDING)
            ->where('delivery_date', '<', date('Y-m-d H:i:s', time()- 48 * 60 * 60))
            ->get();
        dd($books);

        $books->each(function ($item, $key) {
            $defaultLang = $item->user->default_language;
            Mail::send('email.online-service-to-buyer-for-order-auto-completed.' . $defaultLang,
                ['book' => $item],
                function ($message) use ($item, $defaultLang) {
                    $message->from(NOREPLY_EMAIL, REPLY_NAME);
                    $message->to($item->user->email, $item->user->name)
                        ->subject(trans('main.Online.service.to.buyer.for.order.auto.completed.object', [], $defaultLang));
                }
            );

            $item->status = Book::STATUS_PROVIDED;
            $item->notify_status = Book::NOTIFY_STATUS_AUTO_ACCEPT;
            $item->save();
        });        
    }

    public function removeAllBooks($user_id = null)
    {
        if ($user_id) {
            TransactionOfBooking::where(['sender_id' => $user_id])->delete();
            TransactionOfCredit::where(['user_id' => $user_id])->delete();
            Review::where(['reviewer_id' => $user_id])->delete();
            Book::where(['user_id' => $user_id])->delete();
            UserBalance::where(['user_id' => $user_id])->update([
                'balance' => 0,
                'pending_balance' => 0,
            ]);
        } else {
            TransactionOfBooking::where([])->delete();
            TransactionOfCredit::where([])->delete();
            Review::where([])->delete();
            Book::where([])->delete();
            RequestExtendDeliveryDate::where([])->delete();
            RequestModification::where([])->delete();
            UserBalance::where([])->update([
                'balance' => 0,
                'pending_balance' => 0,
            ]);
        }
    }
}
