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

    <p>you have successfully canceled the appointment with {{ $book->user->name }}</p>
    <br>

    <p>Below details of service canceled:</p>

    <p><b>Customer:</b> {{ $book->user->full_name }}</p>

    <p><b>Service:</b> {{ $book->service->name }}</p>

    <p><b>Order number:</b> {{ $book->id }}</p>

    <p>
		<span class="datetime"><b>Date of order:</b> {{ $date_order->format('d-m-Y') }}</span><br />
        <b>Time:</b> {{ $date_order->format('H:i') }}
    </p>
    <br />

    <p>
		<span class="datetime"><b>Date of booking:</b> {{ $date_book->format('d-m-Y') }}</span><br />
        <b>Time:</b> {{ $date_book->format('H:i') }}
    </p>

    <p><b>Duration:</b> {{ $book->prettyDuration }}</p>

    <p><b>Number of services that were booked:</b> {{ $book->number_of_booking }}</p>
    <br />

    <p>
        @if ($book->is_paid_online == 1 && $book->price > 0)
            <b>Price you refunded:</b> €{{ number_format($book->total_amount, 2) }}
        @endif

        @if ($book->is_paid_online == 0 && $book->price > 0)
            <b>Price the customer had to pay:</b> €{{ number_format($book->total_amount, 2) }}
        @endif

        @if ($book->price == 0)
            <b>Price paid:</b> Free Service
        @endif
    </p>

    @if (!empty($bookingTransaction))
        <p><b>Payment transaction No.</b> {{ $bookingTransaction->id }}</p>
    @endif

    @if (!empty($feeTransaction))
        <p><b>Transaction fee No.</b> {{ $feeTransaction->id }}</p>
    @endif
    <br /><br />

    @php
    	\App::setLocale($oldLang);
    @endphp
@stop
