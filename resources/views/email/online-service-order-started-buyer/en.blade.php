@extends('email.layout-en')

@section('email-content')
    @php
        $lang = 'en';
        $oldLang = \App::getLocale();
        \App::setLocale($lang);

        $deliveryDate = new DateTime($book->delivery_date);
        $date_order = new DateTime($book->created_at);
    @endphp

    <p>Hello <b>{{ $book->user->name }}</b>,</p>

    <p>thanks for order with us.</p>

    <p>We sent your order to <b>{{ $book->seller->name }}</b>.</p>
    <br>

    <p>Below details of service ordered:</p>

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

    @if (in_array($paid_type, [\App\Models\Book::PAID_PAYPAL, \App\Models\Book::PAID_CREDIT]))
        <p><b>Payment transaction No.</b> {{ $transaction_id }}</p>
    @endif
    <br><br>

    <p><b>Note:</b></p>

    <p>
        @if (in_array($paid_type, [\App\Models\Book::PAID_PAYPAL, \App\Models\Book::PAID_CREDIT]))
            <b>{{ $book->seller->name }}</b> may request additional files or more informations about your service that
            you can provide from <b><a href="{{ route('user.book.detail', ['id' => $book->id]) }}">the order page</a></b>. On the delivery date
            you'll have to approve the order or, if necessary, request further changes which must be approved by
            <b>{{ $book->seller->name }}</b> and the delivery date may can change.
        @else
            We remind you that the booked service can be canceled up to a maximum of 24 hours before the booking date and
            time. The booked service can be canceled from <b><a href="{{ route('user.book') }}">My Purchases</a></b> page.
        @endif
    </p>

    @php
        \App::setLocale($oldLang);
    @endphp
@stop
