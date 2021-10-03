<?php

namespace App\Repositories;

use App\Models\Book;
use App\Models\Service;
use App\Helpers\PriceHelper;
use App\Models\ServiceOffice;
use App\Models\OnlineOrderMessage;
use Illuminate\Support\Facades\DB;
use App\Models\TransactionOfBooking;
use Illuminate\Support\Facades\Mail;
use App\Models\RequestExtendDeliveryDate;

class BookRepository
{
    public static function getAllowedBooks($service_id, $office_id)
    {
        return ServiceOffice::where([
            'service_id' => $service_id,
            'office_id' => $office_id,
        ])->max('book_count');
    }

    public static function getTakenBooks($service_id, $office_id, $book_date)
    {
        return Book::where([
            'service_id' => $service_id,
            'office_id' => $office_id,
            'book_date' => $book_date,
        ])->where('status', '!=', '2')
        ->sum('number_of_booking');
    }

    public static function getByReviewToken($token)
    {
        return Book::with('user', 'service')
            ->whereRaw("CONCAT_WS('',id,user_id,user_id,service_id,duration,office_id)=$token")
            ->first();
    }

    public static function createBooking($orderDetail, $transactionId = null)
    {
        $service = Service::find($orderDetail["sid"]);

        $options = [];
        if ($orderDetail['provide_online_type'] == Service::PROVIDE_ONLINE_TYPE) {
            $options = [
                'online_delivery_time' => $orderDetail['online_delivery_time'],
            ];
        }

        $totalAmount = $orderDetail['total_amount'];
        $fee = PriceHelper::getFee($orderDetail['actual_price'] ?? 0);
        $totalFee = PriceHelper::getFee($totalAmount);

        $book = new Book;
        $book->user_id = $orderDetail['uid'];
        $book->seller_id = $orderDetail['oid'];
        $book->service_id = $orderDetail['sid'];
        $book->provide_online_type = $orderDetail['provide_online_type'];
        if ($orderDetail['provide_online_type'] == Service::PROVIDE_ONLINE_TYPE) {
            $book->booking_confirm = Service::BOOKING_DIRECTLY;
            $book->online_revision = $service->online_revision;
        } else {
            if ($orderDetail['is_paid_online'] == 1) {
                $book->booking_confirm = $service->booking_confirm;
            } else {
                $book->booking_confirm = Service::BOOKING_CONFIRM;
            }
        }
        $book->payment_id = $transactionId;
        $book->book_date = $orderDetail['book_date'];
        $book->delivery_date = $orderDetail['delivery_date'] ?? null;
        $book->price = $service->price;
        $book->discount = $orderDetail['discount_price'];
        $book->number_of_booking = $orderDetail['number_of_booking'];
        $book->total_amount = $totalAmount;
        $book->fee = $fee;
        $book->total_fee = $totalFee;
        $book->duration = $orderDetail['total_duration'] ?? $service->duration;
        $book->user_address = $orderDetail['address'] ?? '';
        $book->message = $orderDetail['msg'] ?? '';
        $book->office_id = $orderDetail['office_id'];
        $book->status = Book::STATUS_PENDING;

        if ($book->provide_online_type == Service::PROVIDE_OFFLINE_TYPE)
        {
            if ($book->booking_confirm == Service::BOOKING_CONFIRM
                || $orderDetail['is_paid_online'] != 1)
            {
                $book->status = Book::STATUS_WAIT_CONFIRM;
            }
        }

        $book->options = serialize($options);
        $book->is_paid_online = $orderDetail['is_paid_online'];
        $book->payment_type = $orderDetail['payment_type'];
        $book->save();
       
        // pay online: is_paid_online == 1
        if ($orderDetail['is_paid_online'] == 1) {
            if ($transactionId == null) {
                $buyerBalance = $book->user->balance;
                $buyerBalance->balance -= $totalAmount;
                $buyerBalance->save();
            }

            $bookingTransaction = TransactionOfBooking::create([
                'payment_id' => $book->payment_id,
                'transaction_type' => TransactionOfBooking::TYPE_NORMAL,
                'sender_id' => auth()->id(),
                'receiver_id' => $book->seller_id,
                'service_id' => $book->service_id,
                'amount' => $totalAmount,
                'on_hold' => 1,
                'show_user' => 1,
                'book_id' => $book->id,
                'refunded' => 0,
            ]);

            $seller = $book->seller;
            $sellerBalance = $seller->balance;
            $sellerBalance->pending_balance += $book->total_amount;
            $sellerBalance->save();
        } else {
            $bookingTransaction = null;
        }

        return [$book, $bookingTransaction];
    }

    public static function addFileForOnlineOrder($book, $orderDetail)
    {
        OnlineOrderMessage::create([
            'book_id' => $book->id,
            'buyer_id' => $book->user_id,
            'file_name' => $orderDetail['source_file_name'] ?? null,
            'file_path' => $orderDetail['target_file_name'] ?? null,
            'message' => $orderDetail['msg'] ?? '',
        ]);
    }

    public static function notifyUser($book, $bookingTransaction)
    {
        $defaultLang = $book->user->default_language;
        if ($book->provide_online_type == Service::PROVIDE_ONLINE_TYPE) {
            Mail::send('email.online-service-order-started-buyer.' . $defaultLang, [
                'book' => $book,
                'paid_type' => $book->payment_type,
                'transaction_id' => $bookingTransaction->id ?? null,
            ], function ($message) use ($book, $defaultLang) {
                $message->from(NOREPLY_EMAIL, REPLY_NAME)
                    ->to($book->user->email, $book->user->name)
                    ->subject(trans('main.email.order.started.buyer.object', ['seller_name' => $book->seller->name], $defaultLang));
            });
        } else {
            Mail::send('email.booking-confirmed.' . $defaultLang, [
                'book' => $book,
                'paid_type' => $book->payment_type,
                'transaction_id' => $bookingTransaction->id ?? null,
            ], function ($message) use ($book, $defaultLang) {
                $message->from(NOREPLY_EMAIL, REPLY_NAME)
                    ->to($book->user->email, $book->user->name)
                    ->subject(trans('main.email.booking.buyer.confirm.object', ['seller_name' => $book->seller->name], $defaultLang));
            });
        }
    }

    public static function notifyProfessional($book, $bookingTransaction, $feeTransaction)
    {
        $defaultLang = $book->seller->default_language;
        if ($book->provide_online_type == Service::PROVIDE_ONLINE_TYPE) {
            Mail::send('email.online-service-order-started-seller.' . $defaultLang, [
                'book' => $book,
                'paid_type' => $book->payment_type,
                'booking_transaction_id' => $bookingTransaction->id ?? null,
                'fee_transaction_id' => $feeTransaction->id ?? null,
            ], function ($message) use ($book, $defaultLang) {
                $message->from(NOREPLY_EMAIL, REPLY_NAME)
                    ->to($book->seller->email, $book->seller->name)
                    ->subject(trans('main.email.order.started.seller.object', ['buyer_name' => $book->user->name], $defaultLang));
            });
        } else {
            Mail::send('email.booking-confirmed-professional.' . $defaultLang, [
                'book' => $book,
                'paid_type' => $book->payment_type,
                'booking_transaction_id' => $bookingTransaction->id ?? null,
                'fee_transaction_id' => $feeTransaction->id ?? null,
            ], function ($message) use ($book, $defaultLang) {
                $message->from(NOREPLY_EMAIL, REPLY_NAME)
                    ->to($book->seller->email, $book->seller->name)
                    ->subject(trans('main.email.booking.seller.confirm.object', ['buyer_name' => $book->user->name], $defaultLang));
            });
        }
    }

    public static function sendConfirmEmailToUser($book, $bookingTransaction)
    {
        $defaultLang = $book->user->default_language;
        Mail::send('email.booking-request-confirm-buyer.' . $defaultLang, [
            'book' => $book,
            'paid_type' => $book->payment_type,
            'transaction_id' => $bookingTransaction->id ?? null,
        ], function ($message) use ($book, $defaultLang) {
            $message->from(NOREPLY_EMAIL, REPLY_NAME)
                ->to($book->user->email, $book->user->name)
                ->subject(trans('main.email.booking.buyer.confirm.object.request', ['seller_name' => $book->seller->name], $defaultLang));
        });
    }

    public static function sendConfirmEmailToSeller($book, $bookingTransaction)
    {
        $defaultLang = $book->seller->default_language;
        Mail::send('email.booking-request-confirm-seller.' . $defaultLang, [
            'book' => $book,
            'paid_type' => $book->paid_type,
            'booking_transaction_id' => $bookingTransaction->id ?? null,
        ], function ($message) use ($book, $defaultLang) {
            $message->from(NOREPLY_EMAIL, REPLY_NAME)
                ->to($book->seller->email, $book->seller->name)
                ->subject(trans('main.email.booking.seller.confirm.object.wait', ['buyer_name' => $book->user->name], $defaultLang));
        });
    }

    public static function cancelBooking($bookId, $creditSource='without_fee', $fromOnlineProcessing = false, $onlineCancelMsg = '')
    {
        $book = Book::find($bookId);
        $refundTransaction = null;

        DB::beginTransaction();
        try {
            // Refund the payment to the buyer
            $transaction = TransactionOfBooking::where([
                'transaction_type' => TransactionOfBooking::TYPE_NORMAL,
                'on_hold' => 1,
                'sender_id' => $book->user_id,
                'receiver_id' => $book->seller_id,
                'service_id' => $book->service_id,
                'book_id' => $book->id,
            ])->first();
    
            // This will be null if Pay to office booking
            if ($transaction) { // if the transaction exists then create the reverse for a refund    
                $refundTransaction = TransactionOfBooking::create([
                    'transaction_type' => TransactionOfBooking::TYPE_NORMAL_REFUND,
                    'sender_id' => $transaction->receiver_id,
                    'receiver_id' => $transaction->sender_id,
                    'service_id' => $transaction->service_id,
                    'amount' => $transaction->amount,
                    'on_hold' => 0,
                    'book_id' => $book->id,
                ]);
    
                // Also set the original transaction as not on hold
                $transaction->on_hold = 0;
                $transaction->refunded = 1;
                $transaction->save();
    
                $userBalance = $book->user->balance;
                $userBalance->balance += $transaction->amount;
                $userBalance->save();

                $sellerBalance = $book->seller->balance;
                $sellerBalance->pending_balance -= $transaction->amount;
                $sellerBalance->save();
            }

            if ($creditSource == 'balance') {
                $feeTransaction = TransactionOfBooking::create([
                    'payment_id' => null,
                    'transaction_type' => TransactionOfBooking::TYPE_FEE,
                    'sender_id' => $book->seller_id,
                    'receiver_id' => WEBSITE_WALLET,
                    'service_id' => $book->service_id,
                    'amount' => $book->total_fee,
                    'on_hold' => 0,
                    'book_id' => $book->id,
                    'refunded' => 0,
                ]);

                $sellerBalance = $book->seller->balance;
                $sellerBalance->balance -= $book->total_fee;
                $sellerBalance->save();
            }

            $book->status = Book::STATUS_CANCEL;
            $book->deleted_by = 'seller';
            $book->save();

            DB::commit();
        } catch (\Exception $e) {
            DB::rollback();
        }

        if ($fromOnlineProcessing) {
            $message = new OnlineOrderMessage;
            $message->book_id = $book->id;
            $message->seller_id = auth()->id();
            $message->message = '<strong>' . trans('main.Cancelled order by buyer.') . '</strong> <br />';
            $message->message .= $onlineCancelMsg;
            $message->save();

            $defaultLang = $book->user->default_language;
            Mail::send('email.online-service-seller-cancel-book-buyer.' . $defaultLang,
                [
                    'book' => $book,
                ], function ($message) use ($book, $defaultLang) {
                    $message->from(NOREPLY_EMAIL, REPLY_NAME);
                    $message->to($book->user->email, $book->user->full_name)
                        ->subject(trans('main.email.online.service.cancelBySeller.buyer.subject', ['seller_name' => $book->seller->name], $defaultLang));
                }
            );

            $defaultLang = $book->seller->default_language;
            Mail::send('email.online-service-seller-cancel-book-professional.' . $defaultLang,
                [
                    'book' => $book,
                ], function ($message) use ($book, $defaultLang) {
                    $message->from(NOREPLY_EMAIL, REPLY_NAME);
                    $message->to($book->seller->email, $book->seller->full_name)
                        ->subject(trans('main.email.online.service.cancelBySeller.seller.subject', ['buyer_name' => $book->user->name], $defaultLang));
                }
            );
        } else {
            $param = [
                'book' => $book,
                'bookingTransaction' => $refundTransaction,
                'feeTransaction' => null,
            ];
    
            $currentLang = \App::getLocale();
    
            $info2User = [
                'reply_name' => REPLY_NAME,
                'reply_email' => NOREPLY_EMAIL,
                'email' => $book->user->email,
                'name' => $book->user->name,
                'subject' => trans('main.email.booking.cancelBySeller.buyer.subject', ['seller_name' => $book->seller->name]),
            ];
    
            $info2Professional = [
                'reply_name' => REPLY_NAME,
                'reply_email' => NOREPLY_EMAIL,
                'email' => $book->seller->email,
                'name' => $book->seller->name,
                'subject' => trans('main.email.booking.cancelBySeller.seller.subject', ['buyer_name' => $book->user->name], $book->user->default_language),
            ];
    
            Mail::send('email.seller-cancel-book-buyer.' . $book->user->default_language, $param, function ($message) use ($info2User) {
                $message->from($info2User['reply_email'], $info2User['reply_name']);
                $message->to($info2User['email'], $info2User['name'])->subject($info2User['subject']);
            });
    
            Mail::send('email.seller-cancel-book-professional.' . $currentLang, $param, function ($message) use ($info2Professional) {
                $message->from($info2Professional['reply_email'], $info2Professional['reply_name']);
                $message->to($info2Professional['email'], $info2Professional['name'])
                    ->subject($info2Professional['subject']);
            });
        }
        
        session()->flash('alert', ['type' => 'success', 'msg' => trans('main.Status updated successfully!')]);
        return redirect()->route('user.orders.view', ['id' => $bookId]);
    }

    public static function acceptExtendDeliveryDate($request)
    {
        $extendRequestId = $request->input('delivery_extend_request_id');
        $extendDelivery = RequestExtendDeliveryDate::findOrFail($extendRequestId);
        $book = Book::findOrFail($extendDelivery->book_id);

        $extendDelivery->status = RequestExtendDeliveryDate::STATUS_EXTEND_ACCEPT;
        $extendDelivery->save();

        $oldDeliveryDate = $book->delivery_date;
        $book->delivery_date = $extendDelivery->delivery_date;
        $book->save();

        OnlineOrderMessage::create([
            'book_id' => $book->id,
            'buyer_id' => $book->user_id,
            'file_name' => null,
            'file_path' => null,
            'message' => trans('main.accept_extend_delivery_date', ['delivery_date' => date('d/m/Y', strtotime($book->delivery_date))]),
        ]);

        $defaultLang = $book->seller->default_language;

        Mail::send('email.delivery-date-extend-accept.' . $defaultLang,
            [
                'book' => $book,
                'old_delivery_date' => date('d/m/Y', strtotime($oldDeliveryDate)),
                'new_delivery_date' => date('d/m/Y', strtotime($book->delivery_date)),
            ], function ($message) use ($book) {
                $message->from(NOREPLY_EMAIL, REPLY_NAME);
                $message->to($book->seller->email, $book->seller->full_name)
                    ->subject(trans('main.Request to extend delivery date accepted by buyer', ['buyer_name' => $book->user->name]));
            }
        );

        session()->flash('alert', ['type' => 'success', 'msg' => trans("main.Extending delivery date accepted successfully")]);
    }

    public static function cancelExtendDeliveryDate($request)
    {
        $extendRequestId = $request->input('delivery_extend_request_id');
        $extendDelivery = RequestExtendDeliveryDate::findOrFail($extendRequestId);
        $book = Book::findOrFail($extendDelivery->book_id);

        $extendDelivery->status = RequestExtendDeliveryDate::STATUS_EXTEND_DENIED;
        $extendDelivery->save();

        OnlineOrderMessage::create([
            'book_id' => $book->id,
            'buyer_id' => $book->user_id,
            'file_name' => null,
            'file_path' => null,
            'message' => trans('main.cancel_extend_delivery_date', ['delivery_date' => date('d/m/Y', strtotime($extendDelivery->delivery_date))]),
        ]);

        Mail::send('email.delivery-date-extend-cancel.' . app()->getLocale(),
            [
                'book' => $book,
                'new_delivery_date' => $extendDelivery->delivery_date,
                'cancel_reason' => $request->input('message'),
            ], function ($message) use ($book) {
                $message->from(NOREPLY_EMAIL, REPLY_NAME);
                $message->to($book->seller->email, $book->seller->full_name)
                    ->subject(trans('main.Request to extend delivery date canceled by buyer', ['buyer_name' => $book->user->name]));
            }
        );

        session()->flash('alert', ['type' => 'success',
            'msg' => trans("main.Extending delivery date cancelled successfully", ['seller_name' => $book->seller->name])]);
    }
}
