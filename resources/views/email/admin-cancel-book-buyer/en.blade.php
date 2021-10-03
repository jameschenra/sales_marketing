@extends('email.layout-en')

@section('email-content')
    @php
        $lang = "en";
        $oldLang = \App::getLocale();
        \App::setLocale($lang);

        $date_book = new DateTime($book->book_date);
        $date_order = new DateTime($book->created_at);
    @endphp

    <p>Hello <b>{{ $book->user->name }}</b>,</p>

    <p>following your request we have canceled your appointment.</p><br />

    <p> Below details of service canceled:</p>

    <p>
        <b>Service:</b> {{ $book->service->name }}
    </p>

    <p>
        <b>Order number:</b> {{ $book->id }}
    </p>
    <p>
        <span class="datetime"><b>Date of order:</b> {{ $date_order->format('d-m-Y') }}</span>
        <br>
        <b>Time:</b> {{ $date_order->format('H:i') }}
    </p><br>
    <p>
        <span class="datetime"><b>Date of booking:</b> {{ $date_book->format('d-m-Y') }}</span>
        <br><b>Time:</b> {{ $date_book->format('H:i') }}
    </p>

    <p>
        <b>Duration:</b> {{ $book->prettyDuration }}
    </p>

    <p>
        <b>Number of services you had booked:</b> {{ $book->number_of_booking }}
    </p>
    <br>

    <p>
        @if ($book->is_paid_online == 1 && $book->price > 0)
            <b>Price refunded on your own balance:</b> €{{ number_format($book->price, 2) }}
        @endif

        @if ($book->is_paid_online == 0 && $book->price > 0)
            <b>Price you had to pay:</b> €{{ number_format($book->price, 2) }}
        @endif

        @if ($book->price == 0)
            <b>Price paid:</b> Free Service
        @endif
    </p>

    @if (!empty($bookingTransaction))
        <p>
            <b>Payment transaction No.</b> {{ $bookingTransaction->id }}
        </p>
    @endif
    <br><br>
    @if ($book->is_paid_online == 1 && $book->price > 0)
        <p>
            To make a new booking you can login directly from <b><a href="{{ URL::route('user.auth.login') }}">here</a></b>.
            To withdraw money refunded from
            your <b><a href="{{ route('user.balance.show') }}">Balance</a></b> page.</p>
    @else
        <p>
            To make a new booking you can login directly from <b><a href="{{ URL::route('user.auth.login') }}">here</a></b>.
        </p>
    @endif


    @php
    \App::setLocale($oldLang);
    @endphp
@stop
