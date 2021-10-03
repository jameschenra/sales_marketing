<?php

namespace App\Http\Controllers\User;

use App\User;
use DateTime;
use Response;
use Carbon\Carbon;
use App\Models\Book;
use App\Models\Office;
use App\Models\Service;
use App\Helpers\PriceHelper;
use Illuminate\Http\Request;
use App\Helpers\PayPalClient;
use App\Models\ReservedOrder;
use App\Models\ServiceOffice;
use PayPalHttp\HttpException;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use App\Models\TransactionOfBooking;
use App\Models\TransactionOfPayment;
use App\Repositories\BookRepository;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Redirect;
use PayPalCheckoutSdk\Orders\OrdersCreateRequest;
use PayPalCheckoutSdk\Orders\OrdersCaptureRequest;

class BookingController extends Controller
{
    public function availableHours(Request $request, Carbon $carbon)
    {
        $this->validate($request, [
            'date' => 'required|date_format:d/m/Y',
            'service_id' => 'required|numeric',
            'office_id' => 'required|numeric',
        ]);

        $serviceId = $request->input('service_id');
        $officeId = $request->input('office_id');

        $service = Service::with([
            'offices' => function ($query) use ($request, $officeId) {
                return $query->where('office_id', $officeId);
            }, 'offices.office.opening'])
            ->find($serviceId, ['id', 'price', 'duration', 'provide_online_type']);
        $office = Office::find($officeId);

        $day = strtolower($carbon->createFromFormat('d/m/Y', $request->get('date'))->format('D'));
        $startHour = $service->offices->first()->office->opening->{$day . '_start'};
        $endHour = $service->offices->first()->office->opening->{$day . '_end'};

        // check for weekly holiday
        if (strtolower($startHour) == 'closed' || str_contains($office->holidays ?? '', $request->get('date'))) {
            return [];
        }

        $date = DateTime::createFromFormat('d/m/Y', $request->get('date'))->format('Y-m-d');
        $orders = Book::where('office_id', $officeId)
            ->where('service_id', $serviceId)
            ->whereIn('status', [0, 3])
            ->where('book_date', 'LIKE', $date . '%')
            ->get();

        $officeHours = $this->officeHours($service, $request->get('date'), $startHour, $endHour);

        $result = [];
        for ($idx = 0; $idx < count($officeHours); $idx++) {
            $bookDateTime = Carbon::createFromFormat('d/m/Y H:i', $officeHours[$idx]);
            $bookDateTimeFormated = $bookDateTime->format('Y-m-d H:i:s');
            $numberOfBookingAvailable = $service->offices->first()->book_count - $orders->where('book_date', $bookDateTimeFormated)->sum('number_of_booking');

            if ($numberOfBookingAvailable <= 0 ||
                ($bookDateTime->timestamp < Carbon::now()->timestamp)) {
                $numberOfBookingAvailable = 0;
            }

            $curStartHour = $bookDateTime->format('H:i');
            $curEndHour = $bookDateTime->addMinutes($service->duration)->format('H:i');

            $result[] = [
                'hourLabel' => $curStartHour . ' - ' . $curEndHour,
                'hour' => $curStartHour,
                'bookingAvailable' => $numberOfBookingAvailable,
            ];
        };

        return [
            'office_end_time' => $endHour,
            'available_hours' => $result,
        ];
    }

    private function officeHours(Service $service, $date, $startHour, $endHour)
    {
        $officeEndHour = Carbon::createFromFormat('d/m/Y H:i', $date . ' ' . $endHour);
        $serviceStartHour = Carbon::createFromFormat('d/m/Y H:i', $date . ' ' . $startHour);
        $serviceEndHour = Carbon::createFromFormat('d/m/Y H:i', $date . ' ' . $startHour)->addMinutes($service->duration);

        $hours = [];

        while ($serviceEndHour <= $officeEndHour) {
            $hours[] = $serviceStartHour->format('d/m/Y H:i');
            $serviceStartHour->addMinutes($service->duration);
            $serviceEndHour->addMinutes($service->duration);
        }

        return $hours;
    }

    public function booksLeftCount(Request $request)
    {
        $totalBooked = [];
        $allowedBooks = BookRepository::getAllowedBooks($request->service_id, $request->office_id);
        foreach ($request->book_date as $key => $book_date) {
            $totalBooked[$key] = BookRepository::getTakenBooks($request->service_id, $request->office_id, $book_date);
        }
        $takenBooks = collect($totalBooked)->max();
        $booksLeft = $allowedBooks - $takenBooks;
        return response()->json(['books_left' => $booksLeft], 200);
    }

    public function doPurchase(Request $request)
    {
        $appMode = env('APP_MODE', 'DEV');
        if ($appMode != 'LIVE') {
            session()->flash('alert', ['type' => 'danger', 'msg' => trans("main.payment-not-available-temporary-message")]);
            return redirect()->back();
        }

        $bookingData = json_decode($request->input('data'), true);
        $service = Service::find($bookingData['sid']);
        $service->load(['user']);
        if ($service->user_id == Auth::id()) {
            session()->flash('alert', ['type' => 'danger', 'msg' => trans('main.selfBooking.error')]);
            return redirect()->back();
        }

        $isOnline = $bookingData['provide_online_type'] == Service::PROVIDE_ONLINE_TYPE;
        $isOffline = !$isOnline;

        try {
            $user = Auth::user();

            $bookingData['is_paid_online'] = 1;

            if ($isOnline) {
                if ($request->online_file) {
                    $bookingData['source_file_name'] = $request->online_file->getClientOriginalName();
                    $bookingData['target_file_name'] = time() . '.' . $request->online_file->extension();

                    $request->online_file->move(public_path('upload/online_service_files'), $bookingData['target_file_name']);
                }

                $orderDate = date_create_from_format('d/m/Y', $bookingData["book_date"]);
                $bookingData['book_date'] = date_format($orderDate, 'Y-m-d');

                $deliveryDate = date_create_from_format('d/m/Y', $bookingData["delivery_date"]);

                $bookingData['delivery_date'] = date_format($deliveryDate, 'Y-m-d') . ' 18:00';
            } else {
                DB::beginTransaction();

                if (ReservedOrder::isReservedInFiveMins($bookingData['sid'], $bookingData['office_id'], $bookingData['book_date'])) {
                    session()->flash('alert', [
                        'type' => 'danger',
                        'msg' => trans('main.This slot is temporarily unavailable. Try again in 5 minutes'),
                    ]);
                    DB::rollback();
                    return back();
                }

                ReservedOrder::create([
                    'service_id' => $bookingData['sid'],
                    'office_id' => $bookingData['office_id'],
                    'book_date' => $bookingData['book_date'],
                ]);

                $bookingDate = date_create_from_format('d/m/Y H:i', $bookingData["book_date"]);
                $bookingData['book_date'] = date_format($bookingDate, 'Y-m-d H:i:s');

                $bookingData['delivery_date'] = null;
            }

            $totalAmount = $bookingData['total_amount'];
            if ($totalAmount > 0 && (int) $user->wallet_balance < $totalAmount) {
                $invoiceId = time();
                $orderRequest = new OrdersCreateRequest();
                $orderRequest->prefer('return=representation');
                $orderRequest->body = [
                    "intent" => "CAPTURE",
                    "application_context" => [
                        "landing_page" => "NO_PREFERENCE",
                        "cancel_url" => url('/booking/service/' . $service->slug),
                        "return_url" => url('/payment/success'),
                    ],
                    "purchase_units" => [
                        [
                            "reference_id" => $invoiceId,
                            'description' => "Order #{$invoiceId} Invoice",
                            "amount" => [
                                "currency_code" => "EUR",
                                "value" => $totalAmount,
                            ],
                        ],
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
                            $bookingData['payment_type'] = Book::PAID_PAYPAL;
                            session(['order_detail' => $bookingData]);

                            if ($isOffline) {
                                DB::commit();
                            }

                            return redirect($approveLink);
                        }
                    }

                    session()->flash('error', trans('main.paypal_failed'));
                } catch (HttpException $ex) {
                    session()->flash('error', $ex->getMessage());
                }

                if ($isOffline) {
                    DB::rollback();
                }

                return redirect()->route('user.service.show-booking', ['slug' => $service->slug]);
            } else {
                $bookingData['payment_type'] = Book::PAID_CREDIT;
                [$book, $bookingTransaction] = BookRepository::createBooking($bookingData);

                if ($isOnline) {
                    BookRepository::addFileForOnlineOrder($book, $bookingData);
                }

                if ($isOffline) {
                    DB::commit();
                }

                if ($book->booking_confirm == Service::BOOKING_CONFIRM) {
                    BookRepository::sendConfirmEmailToUser($book, $bookingTransaction);
                    BookRepository::sendConfirmEmailToSeller($book, $bookingTransaction);
                    session()->flash('alert', ['type' => 'success', 'msg' => trans("main.booked_success_confirm", ['seller_name' => $book->seller->name])]);
                } else {
                    BookRepository::notifyUser($book, $bookingTransaction);
                    BookRepository::notifyProfessional($book, $bookingTransaction, null);
                    if ($isOnline) {
                        session()->flash('alert', ['type' => 'success', 'msg' => trans('main.You ordered online service successfully.')]);
                    } else {
                        session()->flash('alert', ['type' => 'success', 'msg' => trans('main.You have purchased successfully.')]);
                    }
                }
            }

            return Redirect::route('user.book');
        } catch (\Exception $e) {
            if ($isOffline) {
                DB::rollback();
            }

            session()->flash('alert', ['type' => 'danger', 'msg' => trans("main.payment error")]);
            return redirect()->route('user.service.show-booking', ['slug' => $service->slug]);
        }
    }

    public function paymentSuccess(Request $request)
    {
        $paymentToken = $request->get('token');

        $payment = TransactionOfPayment::where('payment_token', $paymentToken)->first();
        if ($payment) {
            $book = Book::where('payment_id', $payment->id)->firstOrFail();
            session()->flash('alert', ['type' => 'danger', 'msg' => trans('main.Payment token already consumed.')]);
            return Redirect::route('user.service.show-booking', $book->service->slug);
        }

        try {
            $captureRequest = new OrdersCaptureRequest($paymentToken);
            $captureRequest->prefer('return=representation');

            $client = PayPalClient::client();
            $response = $client->execute($captureRequest);
            if ($response->statusCode == 201) {
                $orderDetail = session('order_detail');

                $paymentTransaction = TransactionOfPayment::create([
                    'user_id' => auth()->id(),
                    'amount' => $orderDetail['total_amount'],
                    'payment_token' => $paymentToken,
                    'data' => serialize($response),
                    'description' => 'Booking payment',
                ]);
    
                [$book, $bookingTransaction] = BookRepository::createBooking($orderDetail, $paymentTransaction->id);
    
                if ($orderDetail['provide_online_type'] == Service::PROVIDE_ONLINE_TYPE) {
                    BookRepository::addFileForOnlineOrder($book, $orderDetail);
                }
                
                if ($book->booking_confirm == Service::BOOKING_DIRECTLY) {
                    BookRepository::notifyUser($book, $bookingTransaction);
                    BookRepository::notifyProfessional($book, $bookingTransaction, null);
    
                    if ($book->provide_online_type == Service::PROVIDE_ONLINE_TYPE) {
                        session()->flash('alert', ['type' => 'success', 'msg' => trans('main.You ordered online service successfully.')]);
                    } else {
                        session()->flash('alert', ['type' => 'success', 'msg' => trans('main.You have purchased successfully.')]);
                    }
                }
    
                if ($book->booking_confirm == Service::BOOKING_CONFIRM) {
                    BookRepository::sendConfirmEmailToUser($book, $bookingTransaction);
                    BookRepository::sendConfirmEmailToSeller($book, $bookingTransaction);
    
                    session()->flash('alert', ['type' => 'success', 'msg' => trans("main.booked_success_confirm", ['seller_name' => $book->seller->name])]);
                }
                
                return Redirect::route('user.book');
            }

            session()->flash('alert', ['type' => 'danger', 'msg' => trans("main.paypal_failed")]);
        } catch (HttpException $ex) {
            session()->flash('alert', ['type' => 'danger', 'msg' => $ex->getMessage()]);
        }

        session()->flash('alert', ['type' => 'danger', 'msg' => trans("main.payment confirm error")]);
        return redirect()->back()->withInput();
    }

    public function paymentFailed()
    {
        return 'Error occured while payment';
    }

    public function bookingByOffice(Request $request)
    {
        $bookingData = json_decode($request->input('data'), true);
        $service = Service::find($bookingData['sid']);
        $service->load(['user']);
        if ($service->user_id == Auth::id()) {
            session()->flash('alert', ['type' => 'danger', 'msg' => trans('main.selfBooking.error')]);
            return redirect()->route('user.service.show-booking', ['slug' => $service->slug]);
        }

        $bookingData['is_paid_online'] = 0;
        $bookingDate = date_create_from_format('d/m/Y H:i', $bookingData["book_date"]);
        $bookingData['book_date'] = date_format($bookingDate, 'Y-m-d H:i:s');

        $ordersBooked = Book::Where('book_date', $bookingData['book_date'])->get();

        if (intval(ServiceOffice::where([
            'office_id' => $bookingData['office_id'],
            'service_id' => $bookingData['sid'],
        ])->first()->book_count) - $ordersBooked->count() <= 0) {
            session()->flash('alert', ['type' => 'danger', 'msg' => trans('main.booked date should be different')]);
            return redirect()->route('user.service.show-booking', ['slug' => $service->slug]);
        }

        $bookingData['payment_type'] = Book::PAID_OFFICE;
        [$book, $bookingTransaction] = BookRepository::createBooking($bookingData);

        BookRepository::sendConfirmEmailToUser($book, $bookingTransaction);
        BookRepository::sendConfirmEmailToSeller($book, $bookingTransaction);

        session()->flash('alert', ['type' => 'success', 'msg' => trans('main.booked_success_onsite', ['seller_name' => $book->seller->name])]);

        return Redirect::route('user.book');
    }

    public function bookingByFree(Request $request)
    {
        $bookingData = json_decode($request->input('data'), true);
        $service = Service::find($bookingData['sid']);
        $service->load(['user']);
        if ($service->user_id == Auth::id()) {
            session()->flash('alert', ['type' => 'danger', 'msg' => trans('main.selfBooking.error')]);
            return redirect()->route('user.service.show-booking', ['slug' => $service->slug]);
        }

        $bookingData['is_paid_online'] = 0;
        $bookingDate = date_create_from_format('d/m/Y H:i', $bookingData["book_date"]);
        $bookingData['book_date'] = date_format($bookingDate, 'Y-m-d H:i:s');

        $bookingData['payment_type'] = Book::PAID_FREE;
        [$book, $bookingTransaction] = BookRepository::createBooking($bookingData);

        BookRepository::sendConfirmEmailToUser($book, $bookingTransaction);
        BookRepository::sendConfirmEmailToSeller($book, $bookingTransaction);

        session()->flash('alert', ['type' => 'success', 'msg' => trans('main.booked_success_free', ['seller_name' => $book->seller->name])]);

        return redirect()->route('user.book');
    }

    public function checkAuth()
    {
        return response()->json(true);
    }
}
