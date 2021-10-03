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

    <p>the service has been completed by <b>{{ $book->seller->name }}</b> and needs to be confirmed. </p>
    <br>

    <p>Below details of the service to be confirmed:</p>

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

    <p>Go to the order page to confirm or request changes.</p>
    <br /><br />
    
    <div class="text-align: center">
        <a href="{{ route('user.book.detail', ['id' => $book->id]) }}">
            <button class="view-btn btn-center">Go to order page</button>
        </a>
    </div>
    <br />

    <p><b>Note:</b></p>

    <p>If you don't reply within 48 hours, according to <b><a href="{{ route('user.terms') }}">Terms & Conditions</a></b> signed, the service will be automatically confirmed.</p>
    <br /><br />

    @php
        \App::setLocale($oldLang);
    @endphp
@stop