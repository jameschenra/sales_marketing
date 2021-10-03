@extends('email.layout-en')

@section('email-content')
    @php
        $lang = "en";
        $oldLang = \App::getLocale();
        \App::setLocale($lang);

        $date_book = new DateTime($book->book_date);
        $date_order = new DateTime($book->created_at);
    @endphp

    <p>Hello <b>{{ $book->seller->name }}</b>,</p>

    <p>you have a new appointment confirmed successfully.</p>
    <br>

    <p>Below details of service booked:</p>

    <p><b>Customer:</b> {{ $book->user->full_name }}</p>

    <p><b>Service:</b> {{ $book->service->name }}</p>

    <p><b>Order number:</b> {{ $book->id }}</p>

    <p>
        <span class="datetime"><b>Date of order:</b> {{ $date_order->format('d-m-Y') }}</span>
        <br>
        <b>Time:</b> {{ $date_order->format('H:i') }}
    </p>
    <br>

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

    @isset ($booking_transaction_id)
        <p><b>Payment transaction No.</b> {{ $booking_transaction_id }}</p>
    @endisset
    
    @isset ($fee_transaction_id)
        <p><b>Transaction fee No.</b> {{ $fee_transaction_id }}</p>
    @endisset
    <br>

    <p>
        <b>Location to provide the service:</b>
        {{ $book->user_address ? trans('main.Customers Address') : $book->office->name }}
    </p>

    <p>
        <b>Address:</b>
        {{ $book->user_address ? $book->user_address : $book->office->full_address }}
    </p>

    <p><b>Phone:</b> {{ $book->office->phone_number }}</p>
    <br>

    <p><b>Contact details of:</b> {{ $book->user->full_name }}</p>

    <p><b>Telephone:</b> {{ $book->user->phone }}</p>

    <p><b>Email:</b> {{ $book->user->email }}</p>
    <br>

    @if (!empty($book->message))
        <p><b>Message sent during booking:</b><br>{{ $book->message }}</p>
        <br><br><br>
    @endif

    <p><b>Note:</b></p>

    <p>
        We remind you that 4 (four) days after the date of the confirmed appointment, according to <b><a href="{{ route('user.terms') }}">Terms & Conditions</a></b> signed and in checking the correct execution of what customer purchased,
        you'll be able to withdraw the amount minus a fee that will be withheld.
        If you are unable to provide the requested service, you can cancel the reservation from your
        <b><a href="{{ route('user.orders.index') }}">Customer Orders</a></b>
        page up to a maximum of 24 hours before the date and time of the appointment.
        In case you decide to cancel the requested service,
        the amount paid by the customer will be refunded directly and a fee will be withheld for the management costs.
    </p>


    @php
        \App::setLocale($oldLang);
    @endphp
@stop
