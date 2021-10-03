@extends('email.layout-en')

@section('email-content')
  @php
    $lang = "es";
    $oldLang = \App::getLocale();
    \App::setLocale($lang);
  @endphp

  <div class="content_center">

    <p>
      Hola <b>{{ $book->user->name." ".$book->user->surname }}</b>,</p><br>

    <p>
      <b>{{ $book->user->name.' '.$book->user->surname }} canceló su cita.</b>
    </p>
    <br>

    <p>
      A continuación los detalles del servicio cancelado:
    </p>

    <p>
      <b>Cliente:</b> {{ $book->user->name.' '.$book->user->surname }}
    </p>
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
      <span
          class="datetime"><b>Fecha de pedido:</b> {{ $date_order->format('d-m-Y') }}</span>
      <br>
      <b>Horas:</b> {{ $date_order->format('H:i') }}
    </p>
    <br>
    <p>
      <span
          class="datetime"><b>Fecha de reserva:</b> {{ $date_book->format('d-m-Y') }}</span>
      <br>
      <b>Horas:</b> {{ $date_book->format('H:i') }}
    </p>
    <p>
      <b>Duración:</b> {{ $book->prettyDuration }}
    </p>
    <p>
      <b>Número de servicios reservados:</b> {{ $book->number_of_booking }}
    </p>
    <br>

    <p>
      @if($book->is_paid_online == 1 && $book->price > 0)
        <b>Precio reembolsado al cliente:</b> €{{ number_format($book->price, 2) }}
      @endif

      @if($book->is_paid_online == 0 && $book->price > 0)
        <b>Precio que el cliente deberia haber pagado:</b> €{{ number_format($book->price, 2) }}
      @endif

      @if($book->price == 0)
        <b>Precio pagado:</b> Servicio gratuito
      @endif
    </p>

    @if(!empty($bookingTransaction))
      <p>
        <b>Número de transacción:</b> {{$bookingTransaction->id}}
      </p>
    @endif
    @if(!empty($feeTransaction))
      <p>
        <b>Número de transacción por cuota:</b> {{$feeTransaction->id}}
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

    <p>
      <b>ATENCIÓN</b>
      <br>
      Te recordamos que, de acuerdo con nuestros
      <b><a href="{{route('user.terms')}}">Términos y Condiciones</a></b>, una vez que el servicio reservado
      ha sido cancelado, reembolsará automáticamente al cliente y se retendrá una cuota por los honorarios de
      administración.
    </p>
  </div>

  @php
    \App::setLocale($oldLang);
  @endphp
@stop