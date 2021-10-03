<?php

namespace App\Http\Controllers\User;

use App\Helpers\PayPalClient;
use App\Http\Controllers\Controller;
use App\Http\Requests\User\ChargeRequest;
use App\Http\Requests\User\WithdrawRequest;
use App\Mail\TFAMail;
use App\Models\Book;
use App\Models\Office;
use App\Models\ProfessionCategory;
use App\Models\Service;
use App\Models\ServiceCategory;
use App\Models\TFA;
use App\Models\TransactionOfBooking;
use App\Models\TransactionOfCredit;
use App\Models\TransactionOfPayment;
use App\Models\UserDetail;
use App\Services\BalanceService;
use App\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;
use PayPalCheckoutSdk\Orders\OrdersCaptureRequest;
use PayPalCheckoutSdk\Orders\OrdersCreateRequest;
use PayPalHttp\HttpException;
use Response;
use View;

class BalanceController extends Controller
{
    public function index(Request $request)
    {
        $userId = Auth::id();

        $param['profession_categories'] = ProfessionCategory::all();
        $param['officies'] = Office::all();
        $param['categories'] = ServiceCategory::orderBy('name_' . app()->getLocale(), 'ASC')->get();
        $param['user'] = Auth::user();
        $param['serviceCount'] = Service::where('user_id', Auth::id())->count();
        $param['transactions'] = TransactionOfBooking::where('sender_id', $userId)
            ->orWhere('receiver_id', $userId)
            ->orderBy('id', 'DESC');

        switch ($request->get('filter')) {
            case 'available_balance':
                $availableBooks = Book::where('seller_id', $userId)
                    ->where('status', Book::STATUS_COMPLETED)
                    ->where('price', '!=', 0)
                    ->pluck('id')->toArray();
                $param['transactions'] = TransactionOfBooking::where('receiver_id', $userId)
                    ->orderBy('created_at', 'DESC')
                    ->whereIn('book_id', $availableBooks)->paginate(PAGINATION_SIZE);
                break;
            case 'not_available_balance':
                $unavailableBooks = Book::where('seller_id', $userId)
                    ->whereIn('status', [Book::STATUS_PENDING, Book::STATUS_PROVIDED])
                    ->where('price', '!=', 0)
                    ->pluck('id')->toArray();
                $param['transactions'] = TransactionOfBooking::where('receiver_id', $userId)
                    ->orderBy('created_at', 'DESC')
                    ->whereIn('book_id', $unavailableBooks)->paginate(PAGINATION_SIZE);
                break;
            case 'fees':
                $param['transactions'] = $param['transactions']->where('receiver_id', WEBSITE_WALLET);
                $param['transactions'] = $param['transactions']->paginate(PAGINATION_SIZE);
                break;
            case 'withdraw':
                $param['transactions'] = $param['transactions']->where('sender_id', 0)->where('receiver_id', $userId);
                $param['transactions'] = $param['transactions']->paginate(PAGINATION_SIZE);
                break;
            default:
                $param['transactions'] = $param['transactions']->paginate(PAGINATION_SIZE);
                break;
        }

        if (session('public-with-credit')) {
            session()->forget('public-with-credit');

            return redirect()->route('service.store');
        }

        session()->forget('success');

        if ($alert = session('alert')) {
            $param['alert'] = $alert;
        }

        $userBalance = $param['user']->balance;
        if (!$userBalance->access_balance_first) {
            $param['alert'] = [
                'type' => 'success',
                'msg' => trans('main.balance page first message buyer'),
            ];
            $userBalance->access_balance_first = 1;
            $userBalance->save();
        }

        $param['is_service_completed'] = (auth()->user()->detail->profile_wizard_completed >= UserDetail::SERVICE_COMPLETED);

        return view('user.pages.balance.index', $param);
    }

    public function download(Request $request, $id)
    {
        $transaction = TransactionOfBooking::find($id);
        $filename = md5($id) . '.pdf';

        //return $this->pdf($transaction);

        return response($this->pdf($transaction), 200, [
            'Content-Description' => 'File Transfer',
            'Content-Disposition' => 'attachment; filename="' . $filename . '"',
            'Content-Transfer-Encoding' => 'binary',
            'Content-Type' => 'application/pdf',
        ]);
    }

    public function pdf($data)
    {
        if (!defined('DOMPDF_ENABLE_AUTOLOAD')) {
            define('DOMPDF_ENABLE_AUTOLOAD', false);
        }

        $dompdf = App::make('dompdf.wrapper');
        $dompdf->loadHTML(($this->viewInvoice($data)->render()));
        return $dompdf->stream();
    }

    public function viewInvoice($transaction)
    {
        $user = \Auth::user();
        $service = Service::find($transaction->service_id);
        $book = Book::find($transaction->book_id);

        if ($transaction->receiver_id == 0) {
            if (\App::getLocale() == 'en') {
                return View::make('user.pages.balance.fee-pdf.en', compact('transaction', 'user', 'book', 'service'));
            } elseif (\App::getLocale() == 'es') {
                return View::make('user.pages.balance.fee-pdf.es', compact('transaction', 'user', 'book', 'service'));
            } else {
                return View::make('user.pages.balance.fee-pdf.it', compact('transaction', 'user', 'book', 'service'));
            }
        } else {
            if (\App::getLocale() == 'en') {
                return View::make('user.pages.balance.invoice-pdf.en', compact('transaction', 'user', 'book'));
            } elseif (\App::getLocale() == 'es') {
                return View::make('user.pages.balance.invoice-pdf.es', compact('transaction', 'user', 'book'));
            } else {
                return View::make('user.pages.balance.invoice-pdf.it', compact('transaction', 'user', 'book'));
            }
        }
    }

    public function doRecharge(ChargeRequest $request)
    {
        $appMode = env('APP_MODE', 'DEV');
        if ($appMode != 'LIVE') {
            return redirect()->back()->with([
                'alert' => [
                    'type' => 'danger',
                    'msg' => trans("main.payment-not-available-temporary-message"),
                ],
            ])->withInput();
        }

        $invoiceId = time();
        $orderRequest = new OrdersCreateRequest();
        $orderRequest->prefer('return=representation');
        $paymentData = [
            "reference_id" => $invoiceId,
            'description' => "Order #{$invoiceId} Invoice",
            "amount" => [
                "currency_code" => "EUR",
                "value" => $request->get('amount'),
            ],
        ];
        $orderRequest->body = [
            "intent" => "CAPTURE",
            "application_context" => [
                "landing_page" => "NO_PREFERENCE",
                "return_url" => url('/user/balance/charge/success'),
                "cancel_url" => url('/user/balance/charge/failed'),
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
                    session(['charge_credit_data' => $paymentData]);

                    return redirect($approveLink);
                }
            }

            session()->flash('alert', ['type' => 'danger', 'msg' => trans('main.paypal_failed')]);
        } catch (HttpException $ex) {
            session()->flash('alert', ['type' => 'danger', 'msg' => $ex->getMessage()]);
        }

        redirect()->route('user.balance.show');
    }

    public function confirmRecharge(Request $request)
    {
        $paymentToken = $request->get('token');
        $payment = TransactionOfPayment::where('payment_token', $paymentToken)->first();
        if ($payment) {
            session()->flash('alert', ['type' => 'danger', 'msg' => trans('main.Payment token already consumed.')]);
            return redirect()->route('user.balance.show');
        }

        $paymentData = session('charge_credit_data');

        try {
            $captureRequest = new OrdersCaptureRequest($paymentToken);
            $captureRequest->prefer('return=representation');

            $client = PayPalClient::client();
            $response = $client->execute($captureRequest);

            if ($response->statusCode == 201) {
                $user = auth()->user();
                $amount = $paymentData['amount']['value'];

                $paymentTransaction = TransactionOfPayment::create([
                    'user_id' => $user->id,
                    'amount' => $amount,
                    'payment_token' => $paymentToken,
                    'data' => serialize($response),
                    'description' => 'Add credit',
                ]);

                $transaction = TransactionOfCredit::create([
                    'payment_id' => $paymentTransaction->id,
                    'user_id' => $user->id,
                    'amount' => $amount,
                    'description' => 'Add Credit',
                ]);

                $user->balance->balance += $amount;
                $user->balance->save();

                TransactionOfBooking::create([
                    'payment_id' => $paymentTransaction->id,
                    'transaction_type' => TransactionOfBooking::TYPE_CREDIT,
                    'sender_id' => 0,
                    'receiver_id' => $user->id,
                    'service_id' => 0,
                    'amount' => number_format($amount, 2),
                    'on_hold' => 0,
                    'refunded' => 0,
                    'description' => 'Credit added by manually',
                ]);

                session()->flash('alert', ['type' => 'success', 'msg' => trans("main.Your balance has been topped-up correctly")]);
            } else {
                session()->flash('alert', ['type' => 'danger', 'msg' => trans("main.paypal_failed")]);
            }
        } catch (\Exception $ex) {
            session()->flash('alert', ['type' => 'danger', 'msg' => $ex->getMessage()]);
        }

        return redirect()->route('user.balance.show');
    }

    public function chargeFailed(Request $request)
    {
        return 'payment failed';
    }

    public function withdraw(WithdrawRequest $request)
    {
        $user = Auth::user();
        $provider = 'PayPal';

        $code = TFA::where('user_id', $user->id)
            ->where('code', $request->input('2fa_code'))
            ->first();

        if (!$code) {
            return back()
                ->withInput($request->all())
                ->with('alert', [
                    'type' => 'danger',
                    'msg' => trans('main.balance.withdraw.messages.error_2fa_code'),
                ]);
        }

        try {
            $balanceService = new BalanceService;
            $balanceService->withdraw(
                $user,
                $request->input('email'),
                $request->input('withdraw_amount'),
                $provider
            );
        } catch (Exception $exception) {
            return back()
                ->withInput($request->all())
                ->with('alert', [
                    'type' => 'danger',
                    'msg' => trans('main.balance.withdraw.messages.error'),
                ]);
        }

        $code->delete();

        return redirect()->route('user.balance.show')
            ->with('alert', [
                'type' => 'success',
                'msg' => trans('main.balance.withdraw.messages.success', ['provider' => $provider]),
            ]);
    }

    public function twoFactorRequest(Request $request)
    {
        $user = Auth::user();

        $code = TFA::create([
            'user_id' => $user->id,
            'type' => TFA::WITHDRAW,
            'data' => $request->all(),
        ]);

        Mail::queue(new TFAMail($user, $code, App::getLocale()));

        return Response::json('success', 200);
    }

    public function twoFactorCheck(Request $request)
    {
        $user = Auth::user();

        $code = TFA::where('code', $request->get('code'))->first();

        if (isset($code) && $code->isActive()) {
            return Response::json(['result' => 'success'], 200);
        }

        return Response::json(['result' => 'failed'], 200);
    }
}
