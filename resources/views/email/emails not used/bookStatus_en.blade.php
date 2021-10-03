@extends('email.layout-en')

@section('email-content')
  @php
    $lang = "en";
    $oldLang = \App::getLocale();
    \App::setLocale($lang);
  @endphp

  <div class="content_center">

    <p>
      Hello <b>{{ $book->user->name." ".$book->user->surname }}</b>,</p>
    <br>

    <p>
      <b>Your appointment has been canceled by {{ $book->user->name.' '.$book->user->surname }}</b></p><br>
    <p> Below details of service canceled:
    </p>

    <p>
      <b>Service name:</b> {{ $book->store->name }}
    </p>

    <p>
      <b>Order number:</b> {{ $book->id }}
    </p>
    @php
      $date_book = new DateTime($book->book_date);
      $date_order = new DateTime($book->created_at);

    @endphp
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
      @if($book->is_paid_online == 1 && $book->price > 0)
        <b>Price refunded on your own balance:</b> €{{ number_format($book->price, 2) }}
      @endif

      @if($book->is_paid_online == 0 && $book->price > 0)
        <b>Price you had to pay:</b> €{{ number_format($book->price, 2) }}
      @endif

      @if($book->price == 0)
        <b>Price paid:</b> Free Service
      @endif
    </p>

    @if(!empty($bookingTransaction))
      <p>
        <b>Transaction number:</b> {{$bookingTransaction->id}}
      </p>
    @endif
    <br>

    <p>
      <b>Location where the service was to be
        provided:</b> {{ $book->user_address ? trans('main.Customers Address') : $book->office->name }}
    </p>

    <p>
      <b>Address:</b> {{ $book->user_address ? $book->user_address : $book->office->country->short_name.' '.$book->office->region->name.' '.$book->office->city->name.' '.$book->office->address }}
    </p>

    <p>
      <b>Office Phone:</b> {{ $book->office->telephone }}
    </p><br>

    <p>
      <b>Contact details of</b> {{ $book->user->name.' '.$book->user->surname }}
    </p>
    <p><b>Telephone:</b> {{ $book->user->phone }}</p>
    <p>
      <b>Email:</b> {{ $book->user->email }}
    </p><br>

    @if(!empty($book->message))
      <p>
        <b>Message sent during booking:</b><br>{{ $book->message }}
      </p><br>
    @endif
  </div>

  @if($book->is_paid_online == 1 && $book->price > 0)
    <p>
      To make a new booking or withdraw money, you can login directly from <b><a
            href="{{ URL::route('user.auth.login') }}">here</a></b>.
    </p>
  @else
    <p>
      To make a new booking you can login directly from <b><a href="{{ URL::route('user.auth.login') }}">here</a></b>.
    </p>
  @endif


  @php
    \App::setLocale($oldLang);
  @endphp
@stop