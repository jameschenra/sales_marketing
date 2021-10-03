@extends('email.layout-en')

@section('email-content')
    @php
        $lang = 'en';
        $oldLang = \App::getLocale();
        \App::setLocale($lang);

        $date_book = new DateTime($book->book_date);
        $date_order = new DateTime($book->created_at);
    @endphp

    <p>Hello <b>{{ $book->seller->name }}</b>,</p>

    <p>you have new a booking request to be confirmed.</p>
    <br>

    <p>Below details of service to be confirmed:</p>
    
    <p><b>Customer:</b> {{ $book->user->full_name }}</p>

    <p><b>Service:</b> {{ $book->service->name }}</p>

    <p><b>Order number:</b> {{ $book->id }}</p>

    <p>
        <span class="datetime"><b>Date of order:</b> {{ $date_order->format('d-m-Y') }}</span>
        <br>
        <b>Time:</b> {{ $date_order->format('H:i') }}
    </p>
    <br>

    <p>
        <span class="datetime"><b>Date of booking:</b> {{ $date_book->format('d-m-Y') }}</span>
        <br>
        <b>Time:</b> {{ $date_book->format('H:i') }}
    </p>

    <p><b>Duration:</b> {{ $book->prettyDuration }}</p>

    <p><b>Number of services requested:</b> {{ $book->number_of_booking }}</p>
    <br>

    <p>
        @switch($paid_type)
            @case(\App\Models\Book::PAID_PAYPAL)
                <b>Price paid online with PayPal:</b> €{{ number_format($book->total_amount, 2) }}
                @break

            @case(\App\Models\Book::PAID_CREDIT)
                <b>Price paid online with own credit:</b> €{{ number_format($book->total_amount, 2) }}
                @break

            @case(\App\Models\Book::PAID_OFFICE)
                <b>Price to pay at the booking date:</b> €{{ number_format($book->total_amount, 2) }}
                @break

            @case(\App\Models\Book::PAID_FREE)
                <b>Price paid:</b> Free Service
                @break
        @endswitch
    </p>

    @isset($booking_transaction_id)
        <p><b>Payment transaction No.</b> {{ $booking_transaction_id }}</p>
    @endisset
    <br>

    <p>
        <b>Location to provide the service:</b>
        {{ $book->user_address ? trans('main.Customers Address') : $book->office->name }}
    </p>

    <p>
        <b>Address:</b>
        {{ $book->user_address ? trans('main.Address will be sent after confirmation') : $book->office->full_address }}
    </p>
    <br>

    @if (!empty($book->message))
        <p><b>Message sent during the booking request:</b><br>{{ $book->message }}</p>
        <br><br><br>
    @endif

    <p><b>{{ $book->seller->name }}</b> you have 48 hours to confirm the request.</p>

    <p>{{ trans("main.After confirmation you'll receive contact details of", ['seller_name' => $book->user->name]) }}.</p>
    <br />

    <div class="text-align: center">
        <a href="{{ route('user.orders.view', ['id' => $book->id]) }}"><button class="view-btn btn-center">Go to Customer
                Orders</button></a>
    </div>
    <br><br>

    <p><b>Note:</b></p>

    <p>We remind you that you have 48 hours to confirm the request. If you are unable to confirm within 48 hours from the
        date of the order, the reservation will be automatically canceled and no fee will be withheld. If you confirm the
        booking request, according to <b><a href="{{ route('user.terms') }}">Terms & Conditions</a></b> signed, will be withheld a fee for booking management costs.
    </p>
    <br><br>

    @php
        \App::setLocale($oldLang);
    @endphp
@stop
