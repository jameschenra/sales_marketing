@extends('email.layout-en')

@section('email-content')
    @php
        $lang = "en";
        $oldLang = \App::getLocale();
        \App::setLocale($lang);

        $deliveryDate = new DateTime($book->delivery_date);
        $date_order = new DateTime($book->created_at);
    @endphp

    <p>Hello <b>{{ $book->user->name }}</b>,</p>

    <p>thanks you for accepting and confirming the service provided by <b>{{ $book->seller->name }}</b> </p>
    <br>

    <p>Below details of the provided service:</p>

    <p><b>Service:</b> {{ $book->service->name }}</p>

    <p><b>Order number:</b> {{ $book->id }}</p>

    <p>
        <span class="datetime"><b>Date of order:</b> {{ $date_order->format('d-m-Y') }}</span>
        <br>
        <b>Time:</b> {{ $date_order->format('H:i') }}
    </p>
    <br>

    <p><span class="datetime"><b>Delivery date:</b> {{ $deliveryDate->format('d-m-Y') }}</span></p>

    <p><b>Number of services ordered:</b> {{ $book->number_of_booking }}</p>
    <p><b>Number of revisions included:</b> {{ $book->online_revision == -1 ? trans('main.Unlimited revisions') : $book->online_revision }}</p>
    <br> <br>

    <p>
        @switch($book->payment_type)
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

    @isset ($booking_transaction_id)
        <p><b>Payment transaction No.</b> {{ $booking_transaction_id }}</p>
    @endisset
    <br><br>

    @php
        \App::setLocale($oldLang);
    @endphp
@stop
