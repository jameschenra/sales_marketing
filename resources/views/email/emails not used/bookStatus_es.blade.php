@extends('email.layout-en')

@section('email-content')
  @php
    $lang = "es";
    $oldLang = \App::getLocale();
    \App::setLocale($lang);
  @endphp

  <div class="content_center">

    <p>
      Hola <b>{{ $book->user->name." ".$book->user->surname }}</b>,</p>
    <br>

    <p>
      <b>Tu cita ha sido cancelada por {{ $book->user->name.' '.$book->user->surname }}</b>
    </p>
    <br>

    <p>En seguida los detalles del servicio cancelado:</p>

    <p>
      <b>Servicio:</b> {{ $book->service->name }}
    </p>

    <p>
      <b>Número de pedido:</b> {{ $book->id }}
    </p>
    @php
      $date_book = new DateTime($book->book_date);
      $date_order = new DateTime($book->created_at);

    @endphp
    <p>
      <span class="datetime"><b>Fecha de pedido:</b> {{ $date_order->format('d-m-Y') }}</span>
      <br>
      <b>Horas:</b> {{ $date_order->format('H:i') }}
    </p><br>
    <p>
      <span class="datetime"><b>Fecha de reserva:</b> {{ $date_book->format('d-m-Y') }}</span>
      <br><b>Horas:</b> {{ $date_book->format('H:i') }}
    </p>

    <p>
      <b>Duración:</b> {{ $book->prettyDuration }}
    </p>

    <p>
      <b>Número de servicios que había reservado:</b> {{ $book->number_of_booking }}
    </p>
    <br>

    <p>
      @if($book->is_paid_online == 1 && $book->price > 0)
        <b>Precio reembolsado a tu cuenta:</b> €{{ number_format($book->price, 2) }}
      @endif

      @if($book->is_paid_online == 0 && $book->price > 0)
        <b>Precio que debería haber pagado:</b> €{{ number_format($book->price, 2) }}
      @endif

      @if($book->price == 0)
        <b>Precio pagado:</b>  Servicio gratuito
      @endif
    </p>

    @if(!empty($bookingTransaction))
      <p>
        <b>Número de transacción:</b> {{$bookingTransaction->id}}
      </p>
    @endif
    <br>

    <p>
      <b>Lugar donde se debería haber prestado el
        servicio:</b> {{ $book->user_address ? trans('main.Customers Address') : $book->office->name }}
    </p>

    <p>
      <b>Dirección:</b> {{ $book->user_address ? $book->user_address : $book->office->country->short_name.' '.$book->office->region->name.' '.$book->office->city->name.' '.$book->office->address }}
    </p>

    <p>
      <b>Teléfono de la oficina:</b> {{ $book->office->telephone }}
    </p><br>

    <p>
      <b>Información de contacto de</b> {{ $book->user->name.' '.$book->user->surname }}
    </p>
    <p><b>Teléfono:</b> {{ $book->user->phone }}</p>
    <p>
      <b>Correo electrónico:</b> {{ $book->user->email }}
    </p><br>

    @if(!empty($book->message))
      <p>
        <b>Mensaje enviado durante la reserva:</b><br>{{ $book->message }}
      </p><br>
    @endif
  </div>

  @if($book->is_paid_online == 1 && $book->price > 0)
    <p>
      Para hacer una nueva reserva o retirar la suma que se le reembolsó, puede iniciar sesión directamente
      desde <b><a
            href="{{ URL::route('user.auth.login') }}">aquí</a></b>.
    </p>
  @else
    <p>
      Para hacer una nueva reserva puede iniciar sesión directamente desde <b><a
            href="{{ URL::route('user.auth.login') }}">aquí</a></b>.
    </p>
  @endif


  @php
    \App::setLocale($oldLang);
  @endphp
@stop