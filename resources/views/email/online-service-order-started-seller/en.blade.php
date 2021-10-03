@extends('email.layout-en')

@section('email-content')
    @php
        $lang = 'en';
        $oldLang = \App::getLocale();
        \App::setLocale($lang);

        $deliveryDate = new DateTime($book->delivery_date);
        $date_order = new DateTime($book->created_at);
    @endphp

    <p>Hello <b>{{ $book->seller->name }}</b>,</p>

    <p>you have a new order placed successfully.</p>
    <br>

    <p>Below details of service ordered:</p>

    <p><b>Customer:</b> {{ $book->user->full_name }}</p>

    <p><b>Service:</b> {{ $book->service->name }}</p>

    <p><b>Order number:</b> {{ $book->id }}</p>

    <p>
        <span class="datetime"><b>Date of order:</b> {{ $date_order->format('d-m-Y') }}</span>
        <br>
        <b>Time:</b> {{ $date_order->format('H:i') }}
    </p>
    <br>

    <p><span class="datetime"><b>Expected delivery date:</b> {{ $deliveryDate->format('d-m-Y') }}</span></p>
    <br>

    <p><b>Number of services ordered:</b> {{ $book->number_of_booking }}</p>
    <p><b>Number of revisions included:</b> {{ $book->online_revision == -1 ? trans('main.Unlimited revisions') : $book->online_revision }}</p>
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

    @isset($fee_transaction_id)
        <p><b>Transaction fee No.</b> {{ $fee_transaction_id }}</p>
    @endisset
    <br><br>

    <p>Go to see the order placed by {{ $book->user->name }}.</p>
    <br />

    <div class="text-align: center">
        <a href="{{ route('user.orders.view', ['id' => $book->id]) }}">
            <button class="view-btn btn-center">Go to order page</button>
        </a>
    </div> 
    <br />

    <p><b>Note:</b></p>

    <p>You may request additional files or more informations from your
        <b><a href="{{ route('user.orders.index') }}">Customer Orders</a></b> page. On the delivery date
        <b>{{ $book->user->name }}</b> need to approve the order or, if necessary, request further changes which you must
        approve and, if necessary, change the delivery date. We remind you that 4 (four) days after the date of the order confirmed, according to <b><a href="{{ route('user.terms') }}">Terms & Conditions</a></b> signed and in checking the correct execution of what customer purchased, you'll be able to withdraw the amount minus a fee that will be withheld.
    </p>

    @php
        \App::setLocale($oldLang);
    @endphp
@stop
