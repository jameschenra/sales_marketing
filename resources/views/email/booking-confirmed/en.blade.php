@extends('email.layout-en')

@section('email-content')
    @php
        $lang = 'en';
        $oldLang = \App::getLocale();
        \App::setLocale($lang);

        $date_book = new DateTime($book->book_date);
        $date_order = new DateTime($book->created_at);
    @endphp

    <p>Hello <b>{{ $book->user->name }}</b>,</p>

    <p>thanks for booking with us.</p>

    <p>Your appointment has been confirmed successfully.</p><br>

    <p>Below details of service booked:</p>

    <p><b>Service:</b> {{ $book->service->name }}</p>

    <p><b>Order number:</b> {{ $book->id }}</p>

    <p>
        <span class="datetime"><b>Date of order:</b> {{ $date_order->format('d-m-Y') }}</span>
        <br>
        <b>Time:</b> {{ $date_order->format('H:i') }}
    </p><br>

    @if (array_key_exists('time_period', unserialize($book->options)))
        <p>
            <span class="datetime"><b>Date of booking:</b> {{ $date_book->format('d-m-Y') }}</span>
            <br><b>Time:</b> from {{ \Carbon\Carbon::parse($book->book_date)->format('H:i') }}
            to
            <b>{{ \Carbon\Carbon::parse($book->book_date)->addMinute(unserialize($book->options)['time_period'])->format('H:i') }}</b>
        </p>
    @else
        <p>
            <span class="datetime"><b>Date of booking:</b> {{ $date_book->format('d-m-Y') }}</span>
            <br><b>Time:</b> {{ \Carbon\Carbon::parse($book->book_date)->format('H:i') }}
        </p>
    @endif

    <p><b>Duration:</b> {{ $book->prettyDuration }}</p>

    <p><b>Number of services booked:</b> {{ $book->number_of_booking }}</p>
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
    <br>

    <p>
        <b>Location where the service is provided:</b>
        {{ $book->user_address ? trans('main.Address buyer typed') : $book->office->city->name }}
    </p>

    <p>
        <b>Address:</b>
        {{ $book->user_address ? $book->user_address : $book->office->full_address }}
    </p>

    <p>
        <b>Phone:</b> {{ $book->office->phone_number }}
    </p><br>

    <p><b>Contact details of:</b> {{ $book->seller->full_name }}</p>

    <p><b>Telephone:</b> {{ $book->seller->phone }}</p>

    <p><b>Email:</b> {{ $book->seller->email }}</p>
    <br>

    @if (!empty($book->message))
        <p><b>Message you sent during booking:</b><br>{{ $book->message }}</p>
        <br><br><br>
    @endif


    <p><b>Note:</b></p>

    <p>
        @if (in_array($paid_type, [\App\Models\Book::PAID_PAYPAL, \App\Models\Book::PAID_CREDIT]))
            We remind you that the booked service can be canceled and the full amount refunded up to a maximum of 24 hours
            before the booking date and time. If you cancel within the last 24 hours, according to <b><a href="{{ route('user.terms') }}">Terms & Conditions</a></b> signed, a penalty of 50% will be applied to the
            amount paid. The booked service can be canceled from <b><a href="{{ route('user.book') }}">My Purchases</a></b>
            page.
        @else
            We remind you that the booked service can be canceled up to a maximum of 24 hours before the booking date and
            time. The booked service can be canceled from <b><a href="{{ route('user.book') }}">My Purchases</a></b> page.
        @endif
    </p>

    @php
        \App::setLocale($oldLang);
    @endphp
@stop
