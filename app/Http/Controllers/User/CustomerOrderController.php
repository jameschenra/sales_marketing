<?php

namespace App\Http\Controllers\User;

use App\Helpers\PayPalClient;
use App\Helpers\PriceHelper;
use App\Http\Controllers\Controller;
use App\Models\Book;
use App\Models\OnlineOrderMessage;
use App\Models\RequestExtendDeliveryDate;
use App\Models\RequestModification;
use App\Models\Service;
use App\Models\TransactionOfBooking;
use App\Models\TransactionOfPayment;
use App\Repositories\BookRepository;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use PayPalCheckoutSdk\Orders\OrdersCaptureRequest;
use PayPalCheckoutSdk\Orders\OrdersCreateRequest;
use PayPalHttp\HttpException;

class CustomerOrderController extends Controller
{
    public function index(Request $request)
    {
        $sellerId = auth()->id();
        $filter = $request->get('filter', '');
        $provided = Book::where('seller_id', $sellerId)
            ->whereIn('status', [Book::STATUS_PROVIDED, Book::STATUS_COMPLETED])
            ->orderBy('created_at', 'DESC')
            ->paginate(PAGINATION_SIZE);
        $pending = Book::where('seller_id', $sellerId)
            ->where('status', Book::STATUS_PENDING)
            ->orderBy('created_at', 'DESC')
            ->paginate(PAGINATION_SIZE);
        $waiting_confirm = Book::where('seller_id', $sellerId)
            ->where('status', Book::STATUS_WAIT_CONFIRM)
            ->orderBy('created_at', 'DESC')
            ->paginate(PAGINATION_SIZE);
        $canceled_by_user = Book::where('seller_id', $sellerId)
            ->where('status', Book::STATUS_CANCEL)
            ->where('deleted_by', 'buyer')
            ->orderBy('created_at', 'DESC')
            ->paginate(PAGINATION_SIZE);
        $canceled_by_you = Book::where('seller_id', $sellerId)
            ->where('status', Book::STATUS_CANCEL)
            ->where('deleted_by', 'user')
            ->orderBy('created_at', 'DESC')
            ->paginate(PAGINATION_SIZE);
        $all = Book::where('seller_id', $sellerId)
            ->orderBy('created_at', 'DESC')
            ->paginate(PAGINATION_SIZE);

        switch ($filter) {
            case 'provided':
                $param['books'] = $provided;
                break;
            case 'pending':
                $param['books'] = $pending;
                break;
            case 'wait_confirm':
                $param['books'] = $waiting_confirm;
                break;
            case 'canceled_by_user':
                $param['books'] = $canceled_by_user;
                break;
            case 'canceled_by_you':
                $param['books'] = $canceled_by_you;
                break;
            default:
                $param['books'] = $all;
        }

        $param['provided_count'] = $provided->count();
        $param['pending_count'] = $pending->count();
        $param['waiting_confirm_count'] = $waiting_confirm->count();
        $param['canceled_by_user_count'] = $canceled_by_user->count();
        $param['canceled_by_you_count'] = $canceled_by_you->count();
        $param['all_count'] = $all->count();
        $param['pageNo'] = 3;
        $param['filter'] = $filter;

        return view('user.pages.order.index', $param);
    }

    public function view($id)
    {
        $book = Book::where(['id' => $id, 'seller_id' => auth()->id()])->firstOrFail();
        $param['user'] = auth()->user();
        $param['book'] = $book;
        $param['fee'] = number_format(PriceHelper::getFee($param['book']->price), 2, ',', '');

        return view('user.pages.order.view', $param);
    }

    public function acceptOrder(Request $request)
    {
        $bookId = $request->input('book_id');
        $book = Book::findOrFail($bookId);
        $feeTransaction = null;

        if ($book->status == Book::STATUS_WAIT_CONFIRM) {
            if ($book->is_paid_online == 0) {
                $seller = $book->seller;
                if ($seller->wallet_balance < $book->total_fee) {
                    return $this->chargeFromOnline($book);
                } else {
                    TransactionOfBooking::create([
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

                    $seller->balance->balance -= $book->total_fee;
                    $seller->balance->save();
                }
            }

            $book->status = Book::STATUS_PENDING;
            $book->save();

            $bookingTransaction = TransactionOfBooking::getTransactionByBookId($bookId);
            BookRepository::notifyUser($book, $bookingTransaction);
            BookRepository::notifyProfessional($book, $bookingTransaction, null);
        }

        session()->flash('alert', ['type' => 'success', 'msg' => trans("main.booking accepted")]);
        return redirect()->route('user.orders.view', ['id' => $bookId]);
    }

    public function cancelOrder(Request $request)
    {
        $id = $request->input('book_id');

        $book = Book::find($id);
        if ($book->status == Book::STATUS_CANCEL) {
            return back();
        }

        $user = auth()->user();
        $fromOnlineProcessing = false;
        if ($book->provide_online_type == Service::PROVIDE_OFFLINE_TYPE) {
            if ($book->is_paid_online == 1) {
                if ($book->booking_confirm == Service::BOOKING_DIRECTLY || $book->status == Book::STATUS_PENDING) {
                    if ($book->total_fee <= $user->wallet_balance) {
                        return BookRepository::cancelBooking($id, 'balance');
                    } else {
                        return $this->chargeFromOnline($book, 'cancel_order');
                    }
                }
            }
        } else {
            if ($request->input('online_service_processing')) {
                $fromOnlineProcessing = true;
            }
        }

        return BookRepository::cancelBooking($id, 'without_fee', $fromOnlineProcessing, $request->input('message'));
    }

    public function sendMessage(Request $request)
    {
        if ($request->online_file) {
            $sourceFileName = $request->online_file->getClientOriginalName();
            $targetFileName = time();
            if ($request->online_file->extension()) {
                $targetFileName .= '.' . $request->online_file->extension();
            }

            $request->online_file->move(public_path('upload/online_service_files'), $targetFileName);
        }

        $bookId = $request->input('book_id');
        $book = Book::find($bookId);

        $model = new OnlineOrderMessage;
        $model->book_id = $bookId;
        $model->seller_id = auth()->id();

        if ($request->online_file) {
            $model->file_name = $sourceFileName;
            $model->file_path = $targetFileName;
        }

        $model->message = $request->input('message');
        $model->save();

        $this->sendNotifyForMessage($book, $model);

        return back();
    }

    public function requestExtend(Request $request)
    {
        $bookId = $request->input('book_id');
        $newDeliveryDate = $request->input('new_delivery_date');
        $book = Book::findOrFail($bookId);

        $extendDelivery = new RequestExtendDeliveryDate();
        $extendDelivery->book_id = $bookId;
        $extendDelivery->delivery_date = $newDeliveryDate;
        $extendDelivery->status = RequestExtendDeliveryDate::STATUS_EXTEND_REQUEST;
        $extendDelivery->save();

        $deliveryDate = date('d/m/Y', strtotime($book->delivery_date));
        $message = trans('main.request_new_delivery_date', ['delivery_date' => date('d/m/Y', strtotime($newDeliveryDate))]) . '<br />';
        $message .= trans('main.Message for request') . ': ' . $request->input('extend_reason');

        OnlineOrderMessage::create([
            'book_id' => $bookId,
            'seller_id' => $book->seller_id,
            'file_name' => null,
            'file_path' => null,
            'message' => $message,
        ]);

        $defaultLang = $book->user->default_language;
        Mail::send('email.delivery-date-extend-request.' . $defaultLang,
            [
                'book' => $book,
                'new_delivery_date' => $newDeliveryDate,
                'request_message' => $message,
            ], function ($message) use ($book, $defaultLang) {
                $message->from(NOREPLY_EMAIL, REPLY_NAME);
                $message->to($book->user->email, $book->user->full_name)
                    ->subject(trans('main.Request to extend delivery date to buyer', ['seller_name' => $book->seller->name], $defaultLang));
            }
        );

        session()->flash('alert', ['type' => 'success', 'msg' => trans('main.Request to extend delivery date sent successfully')]);

        return redirect()->route('user.orders.view', ['id' => $bookId]);
    }

    public function acceptModify(Request $request)
    {
        $bookId = $request->input('book_id');
        $book = Book::findOrFail($bookId);

        $modifyRequested = $book->modification_request;
        $modifyRequested->status = RequestModification::STATUS_MODIFY_ACCEPT;
        $modifyRequested->save();

        $message = new OnlineOrderMessage;
        $message->book_id = $bookId;
        $message->seller_id = auth()->id();

        $message->message = trans('main.Request Modification accepted by buyer');
        $message->save();

        $defaultLang = $book->user->default_language;
        Mail::send('email.online-service-seller-accept-modification.' . $defaultLang,
            [
                'book' => $book,
            ], function ($message) use ($book) {
                $message->from(NOREPLY_EMAIL, REPLY_NAME);
                $message->to($book->user->email, $book->user->full_name)
                    ->subject(trans('main.Accepted modification by seller', ['seller_name' => $book->seller->name]));
            }
        );

        session()->flash('alert', ['type' => 'success', 'msg' => trans('main.You accepted modification')]);
        return redirect()->route('user.orders.view', ['id' => $bookId]);
    }

    public function acceptModifyExtend(Request $request)
    {
        $bookId = $request->input('book_id');
        $book = Book::findOrFail($bookId);

        $modifyRequested = $book->modification_request;
        $modifyRequested->status = RequestModification::STATUS_MODIFY_ACCEPT_EXTEND;
        $modifyRequested->save();

        $msgModel = new OnlineOrderMessage;
        $msgModel->book_id = $bookId;
        $msgModel->seller_id = auth()->id();

        $oldDeliveryDate = date('d/m/Y', strtotime($book->delivery_date));
        $newDeliveryDate = $request->input('new_delivery_date');

        $message = trans('main.Request Modification accepted by buyer and extended delivery date') . '<br />';
        $message .= trans('main.request_new_delivery_date', ['delivery_date' => date('d/m/Y', strtotime($newDeliveryDate))]) . '<br />';
        $message .= trans('main.Message for request') . ': ' . $request->input('extend_reason');
        $msgModel->message = $message;
        $msgModel->save();

        $book->delivery_date =$newDeliveryDate;
        $book->save();

        $defaultLang = $book->user->default_language;
        Mail::send('email.online-service-seller-accept-modification-extend.' . $defaultLang,
            [
                'book' => $book,
            ], function ($message) use ($book) {
                $message->from(NOREPLY_EMAIL, REPLY_NAME);
                $message->to($book->user->email, $book->user->full_name)
                    ->subject(trans('main.Accepted modification by seller', ['seller_name' => $book->seller->name]));
            }
        );

        session()->flash('alert', ['type' => 'success', 'msg' => trans('main.You accepted modification and extended')]);
        return redirect()->route('user.orders.view', ['id' => $bookId]);
    }

    private function chargeFromOnline($book, $type = 'accept_order')
    {
        $invoiceId = time();

        $orderRequest = new OrdersCreateRequest();
        $orderRequest->prefer('return=representation');

        $paymentData = [
            "reference_id" => $invoiceId,
            'description' => "Order #{$invoiceId} Invoice",
            "amount" => [
                "currency_code" => "EUR",
                "value" => $book->total_fee,
            ],
        ];

        $orderRequest->body = [
            "intent" => "CAPTURE",
            "application_context" => [
                "landing_page" => "NO_PREFERENCE",
                "return_url" => url('/credit-payment/success'),
                "cancel_url" => url('/customer-orders/view/' . $book->id),
            ],
            "purchase_units" => [
                $paymentData,
            ],
        ];

        try {
            $client = PayPalClient::client();
            $order = $client->execute($orderRequest);

            if ($order->statusCode == 201) {
                $orderId = $order->result->id;
                $approveLink = '';
                for ($i = 0; $i < count($order->result->links); ++$i) {
                    $link = $order->result->links[$i];
                    if ($link->rel == 'approve') {
                        $approveLink = $link->href;
                    }
                }

                if ($approveLink != '') {
                    session(['order_data' => [
                        'book_id' => $book->id,
                        'payment_data' => $paymentData,
                        'action_type' => $type,
                    ]]);

                    return redirect($approveLink);
                }
            }

            session()->flash('alert', ['type' => 'danger', 'msg' => trans('main.paypal_failed')]);
        } catch (HttpException $ex) {
            session()->flash('alert', ['type' => 'danger', 'msg' => $ex->getMessage()]);
        }

        return redirect()->route('user.book.detail', ['id' => $book->id]);
    }

    public function paymentSuccess(Request $request)
    {
        $orderData = session('order_data');
        $paymentData = $orderData['payment_data'];

        $paymentToken = $request->get('token');
        $payment = TransactionOfPayment::where('payment_token', $paymentToken)->first();
        if ($payment) {
            session()->flash('alert', ['type' => 'danger', 'msg' => trans('main.Payment token already consumed.')]);
            return redirect()->route('user.orders.view', ['id' => $orderData['book_id']]);
        }

        try {
            $captureRequest = new OrdersCaptureRequest($paymentToken);
            $captureRequest->prefer('return=representation');

            $client = PayPalClient::client();
            $response = $client->execute($captureRequest);

            if ($response->statusCode == 201) {
                $book = Book::find($orderData['book_id']);
                $amount = $paymentData['amount']['value'];

                $paymentTransaction = TransactionOfPayment::create([
                    'user_id' => auth()->id(),
                    'amount' => $amount,
                    'payment_token' => $paymentToken,
                    'data' => serialize($response),
                    'description' => 'Payment for fee',
                ]);

                $feeTransaction = TransactionOfBooking::create([
                    'payment_id' => $paymentTransaction->id,
                    'transaction_type' => TransactionOfBooking::TYPE_FEE,
                    'sender_id' => $book->seller_id,
                    'receiver_id' => WEBSITE_WALLET,
                    'service_id' => $book->service_id,
                    'amount' => $amount,
                    'on_hold' => 0,
                    'book_id' => $book->id,
                    'refunded' => 0,
                ]);

                if ($orderData['action_type'] == 'accept_order') {
                    $book->status = Book::STATUS_PENDING;
                    $book->save();

                    BookRepository::notifyUser($book, null);
                    BookRepository::notifyProfessional($book, null, $feeTransaction);

                    session()->flash('alert', ['type' => 'success', 'msg' => trans("main.booking accepted")]);
                } else { // cancel order with add credit
                    return BookRepository::cancelBooking($book->id, 'remote');
                }
            } else {
                session()->flash('alert', ['type' => 'danger', 'msg' => trans("main.accept failed")]);
            }
        } catch (\Exception $ex) {
            session()->flash('alert', ['type' => 'danger', 'msg' => $ex->getMessage()]);
        }

        return redirect()->route('user.orders.view', ['id' => $book->id]);
    }

    private function sendNotifyForMessage($book, $messageModel)
    {
        $param = [
            'book' => $book,
            'messageModel' => $messageModel,
        ];

        $defaultLang = $book->user->default_language;
        Mail::send('email.online-service-seller-upload-file.' . $defaultLang, $param,
            function ($message) use ($book, $defaultLang) {
                $message->from(NOREPLY_EMAIL, REPLY_NAME);
                $message->to($book->user->email)
                    ->subject(trans('main.Seller upload file', ['seller_name' => $book->seller->name], $defaultLang));
            }
        );
    }
}
