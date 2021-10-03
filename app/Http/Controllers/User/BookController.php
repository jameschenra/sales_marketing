<?php

namespace App\Http\Controllers\User;

use App\User;
use Response;
use App\Models\Book;
use App\Models\Service;
use App\Helpers\PriceHelper;
use Illuminate\Http\Request;
use App\Models\OnlineOrderMessage;
use Illuminate\Support\Facades\DB;
use App\Models\RequestModification;
use App\Http\Controllers\Controller;
use App\Models\TransactionOfBooking;
use App\Repositories\BookRepository;
use Illuminate\Support\Facades\Mail;

class BookController extends Controller
{
   
    public function list()
    {
        $param['books'] = Book::where('user_id', auth()->id())
            ->orderBy('created_at', 'DESC')
            ->paginate(PAGINATION_SIZE);

        foreach($param['books'] as $idx => $book) {
            $param['books'][$idx]['fee'] = PriceHelper::getFee($book->price);
        }

        return view('user.pages.book.list', $param);
    }

    public function detail($id) {
        $book = Book::where(['id' => $id, 'user_id' => auth()->id()])->firstOrFail();
        $bookOption = unserialize($book->options);

        $param['book'] = $book;
        $param['service'] = $book->service;

        return view('user.pages.book.detail', $param);
    }

    public function cancelBook(Request $request)
    {
        $bookId = $request->get('book_id');
        $book = Book::find($bookId);

        if ($book->status == Book::STATUS_CANCEL) {
            $result['result'] = "failed";
            $result['msg'] = trans('main.The booking cancelled already');

            return Response::json($result, 200);
        }

        $buyerId = $book->user_id;
        $sellerId = $book->seller_id;
        $serviceId = $book->service_id;
        $seller = User::find($sellerId);
        $buyer = User::find($buyerId);
        $service = Service::find($serviceId);

        $refundAmount = 0;
        $feeAmount = 0;

        DB::beginTransaction();
        try {
            // Refund the payment to the user
            $bookingTransaction = TransactionOfBooking::where(
                [
                    'transaction_type' => TransactionOfBooking::TYPE_NORMAL,
                    'on_hold' => 1,
                    'sender_id' => $buyerId,
                    'receiver_id' => $sellerId,
                    'service_id' => $serviceId,
                    'book_id' => $bookId,
                ]
            )->first();

            // This will be null if Pay to office booking or free booking
            $refundTransaction = null;
            if ($bookingTransaction) { // if the transaction exists then create the reverse for a refund
                if ($book->is_paid_online == 1) {
                    if ($book->isBeforeBook24Hrs()) {
                        $refundAmount = $bookingTransaction->amount;
                    } else {
                        $feeAmount = round($book->total_amount / 2, 2);
                        $refundAmount = $bookingTransaction->amount - $feeAmount;
    
                        TransactionOfBooking::create([
                            'payment_id' => null,
                            'transaction_type' => TransactionOfBooking::TYPE_FEE,
                            'sender_id' => $book->user_id,
                            'receiver_id' => $book->seller_id, // the website itself
                            'service_id' => $book->service_id,
                            'amount' => $feeAmount,
                            'on_hold' => 0,
                            'book_id' => $book->id,
                            'refunded' => 0,
                        ]);
                    }
                }

                $refundTransaction = TransactionOfBooking::create([
                    'transaction_type' => TransactionOfBooking::TYPE_NORMAL_REFUND,
                    'sender_id' => $sellerId,
                    'receiver_id' => $buyerId,
                    'service_id' => $serviceId,
                    'amount' => $bookingTransaction->amount,
                    'on_hold' => 0,
                    'book_id' => $bookId,
                ]);

                // Also set the original transaction as not on hold
                $bookingTransaction->on_hold = 0;
                $bookingTransaction->refunded = 1;
                $bookingTransaction->save();

                $buyerBalance = $book->user->balance;
                $buyerBalance->balance += $refundAmount;
                $buyerBalance->save();

                $sellerBalance = $book->seller->balance;
                $sellerBalance->pending_balance -= $bookingTransaction->amount;
                $sellerBalance->balance += $feeAmount;
                $sellerBalance->save();
            }

            $refundFeeTransaction = null;
            if ($book->is_paid_online == 0) {
                // Refund the fee charged to the professional
                $feeTransaction = TransactionOfBooking::where(
                    [
                        'transaction_type' => TransactionOfBooking::TYPE_FEE,
                        'sender_id' => $sellerId,
                        'receiver_id' => WEBSITE_WALLET,
                        'service_id' => $serviceId,
                        'book_id' => $bookId,
                    ]
                )->orderBy('created_at', 'DESC')->first();

                if ($feeTransaction) { // if the transaction exists then create the reverse for a refund
                    $refundFeeTransaction = TransactionOfBooking::create([
                        'transaction_type' => TransactionOfBooking::TYPE_FEE_REFUND,
                        'sender_id' => WEBSITE_WALLET,
                        'receiver_id' => $sellerId,
                        'service_id' => $serviceId,
                        'amount' => $feeTransaction->amount,
                        'on_hold' => 0,
                        'book_id' => $bookId,
                    ]);

                    $feeTransaction->refunded = 1;
                    $feeTransaction->save();

                    $sellerBalance = $book->seller->balance;
                    $sellerBalance->balance += $feeTransaction->amount;
                    $sellerBalance->save();
                }
            }

            // Set book status as cancelled
            $book->status = Book::STATUS_CANCEL;
            $book->deleted_by = 'buyer';
            $book->save();

            DB::commit();
        } catch (\Exception $e) {
            DB::rollback();

            $result['result'] = "failed";
            $result['msg'] = trans('main.Error occured while cancel book');

            return Response::json($result, 200);
        }

        $param1 = [
            'email' => $seller->email,
            'name' => $seller->name,
            'service' => $service,
            'book' => $book,
            'bookingTransaction' => $refundTransaction,
            'feeTransaction' => $refundFeeTransaction,
            'refund_amount' => $refundAmount,
        ];

        $param2 = [
            'email' => $buyer->email,
            'name' => $buyer->name,
            'service' => $service,
            'book' => $book,
            'bookingTransaction' => $refundTransaction,
            'refund_amount' => $refundAmount,
        ];

        $defaultLang = $book->seller->default_language;
        Mail::send('email.buyer-cancel-book-professional.' . $defaultLang, $param1,
            function ($message) use ($param1, $buyer, $defaultLang) {
                $message->from(NOREPLY_EMAIL, REPLY_NAME);
                $message->to($param1['email'])
                    ->subject(trans('main.email.booking.cancelByBuyer.seller.subject', ['buyer_name' => $buyer->name], $defaultLang));
            }
        );

        $defaultLang = $book->user->default_language;
        Mail::send('email.buyer-cancel-book-buyer.' . $defaultLang, $param2,
            function ($message) use ($param2, $seller, $defaultLang) {
                $message->from(NOREPLY_EMAIL, REPLY_NAME);
                $message->to($param2['email'])
                    ->subject(trans('main.email.booking.cancelByBuyer.buyer.subject', ['seller_name' => $seller->name], $defaultLang));
            }
        );

        $result['result'] = "success";
        $result['msg'] = trans('main.message for service canceled by buyer');

        return Response::json($result, 200);
    }

    public function sendMessage(Request $request)
    {
        $request->validate([
            'message' => 'required',
        ]);

        $bookId = $request->input('book_id');
        $book = Book::findOrFail($bookId);

        $this->createMessage($request, $book);
        session()->flash('alert', ['type' => 'success', 'msg' => trans('main.You have uploaded file successfully.')]);

        return back();
    }

    public function acceptResult(Request $request)
    {
        $book = Book::findOrFail($request->input('book_id'));
        $book->status = Book::STATUS_PROVIDED;
        $book->accepted_date = now();
        $book->save();

        $param = [
            'book' => $book,
        ];

        $defaultLang = $book->user->default_language;
        Mail::send('email.online-service-to-buyer-for-order-completed.' . $defaultLang, $param,
            function ($message) use ($book, $defaultLang) {
                $message->from(NOREPLY_EMAIL, REPLY_NAME);
                $message->to($book->user->email)
                    ->subject(trans('main.You accepted successfully.', [], $defaultLang));
            }
        );

        $defaultLang = $book->seller->default_language;
        Mail::send('email.online-service-buyer-accept.' . $book->seller->default_language, $param,
            function ($message) use ($book, $defaultLang) {
                $message->from(NOREPLY_EMAIL, REPLY_NAME);
                $message->to($book->seller->email)
                    ->subject(trans('main.Online service accepted by customer', ['buyer_name' => $book->user->name], $defaultLang));
            }
        );

        session()->flash('alert', ['type' => 'success', 'msg' => trans('main.You accepted successfully.')]);

        return back();
    }

    public function requestModification(Request $request)
    {
        $request->validate([
            'message' => 'required',
        ]);

        $bookId = $request->input('book_id');
        $book = Book::findOrFail($bookId);

        $this->createMessage($request, $book, 'request_modify');
        session()->flash('alert', ['type' => 'success', 'msg' => trans('main.Modification has been requested successfully.')]);
        return back();
    }

    public function acceptExtendDeliveryDate(Request $request)
    {
        BookRepository::acceptExtendDeliveryDate($request);
        // return redirect()->route('user.book.detail', ['id' => $book->id]);
        return back();
    }

    public function cancelExtendDeliveryDate(Request $request)
    {
        $request->validate([
            'message' => 'required',
        ]);

        BookRepository::cancelExtendDeliveryDate($request);
        return back();
    }

    private function createMessage(Request $request, $book, $type = 'normal')
    {
        if ($request->online_file) {
            $sourceFileName = $request->online_file->getClientOriginalName();
            $targetFileName = time();
            if ($request->online_file->extension()) {
                $targetFileName .= '.' . $request->online_file->extension();
            }            
    
            $request->online_file->move(public_path('upload/online_service_files'), $targetFileName);
        }

        $model = new OnlineOrderMessage;
        $model->book_id = $book->id;
        $model->buyer_id = auth()->id();
        if ($type == 'request_modify') {
            RequestModification::create([
                'book_id' => $book->id,
                'description' => $request->input('message'),
                'status' => RequestModification::STATUS_MODIFY_REQUEST,
                'extension_date' => null,
            ]);
        }
        
        if ($request->online_file) {
            $model->file_name = $sourceFileName;
            $model->file_path = $targetFileName;
        }

        if ($type == 'request_modify') {
            $model->message = '<strong>' . trans('main.Requested modification from buyer.') . '</strong> <br />';
            $model->message .= $request->input('message');
        } else {
            $model->message = $request->input('message');
        }
        
        $model->save();

        $this->sendNotifyForMessage($book, $model, $type);
    }

    private function sendNotifyForMessage($book, $messageModel, $type)
    {
        $param = [
            'book' => $book,
            'messageModel' => $messageModel,
        ];
        
        $defaultLang = $book->seller->default_language;
        if ($type == 'request_modify') {
            Mail::send('email.online-service-buyer-request-modification.' . $defaultLang, $param,
                function ($message) use ($book, $defaultLang) {
                    $message->from(NOREPLY_EMAIL, REPLY_NAME);
                    $message->to($book->seller->email)
                        ->subject(trans('main.email.order.completed.buyer.request.modification.subject', [
                            'buyer_name' => $book->user->name
                        ], $defaultLang));
                }
            );
        } else {
            Mail::send('email.online-service-buyer-upload-file.' . $defaultLang, $param,
                function ($message) use ($book, $defaultLang) {
                    $message->from(NOREPLY_EMAIL, REPLY_NAME);
                    $message->to($book->seller->email)
                        ->subject(trans('main.Buyer sent message', ['buyer_name' => $book->user->name], $defaultLang));
                }
            );
        }
    }
}
