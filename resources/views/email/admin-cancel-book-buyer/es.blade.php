@extends('email.layout-en')

@section('email-content')
    @php
        $lang = "es";
        $oldLang = \App::getLocale();
        \App::setLocale($lang);

        $date_book = new DateTime($book->book_date);
        $date_order = new DateTime($book->created_at);
    @endphp

    <p>Hola <b>{{ $book->user->name }}</b>,</p>

    <p>tras tu solicitud, hemos cancelado tu cita.</p><br />

    <p>En seguida los detalles del servicio cancelado</p>

    <p>
        <b>Servicio:</b> {{ $book->user->name }}
    </p>

    <p>
        <b>Número de pedido:</b> {{ $book->id }}
    </p>
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
        @if ($book->is_paid_online == 1 && $book->price > 0)
            <b>Precio reembolsado a tu cuenta:</b> €{{ number_format($book->price, 2) }}
        @endif

        @if ($book->is_paid_online == 0 && $book->price > 0)
            <b>Precio que debería haber pagado:</b> €{{ number_format($book->price, 2) }}
        @endif

        @if ($book->price == 0)
            <b>Precio pagado:</b> Servicio gratuito
        @endif
    </p>

    @if (!empty($bookingTransaction))
        <p>
            <b>Operación pago no:</b> {{ $bookingTransaction->id }}
        </p>
    @endif
    <br><br>
    @if ($book->is_paid_online == 1 && $book->price > 0)
        <p>
            Para hacer una nueva reserva puede iniciar sesión directamente
            desde <b><a href="{{ URL::route('user.auth.login') }}">aquí</a></b>. Puedes retirar la suma que se te reembolsó
            desde tu página del <b><a href="{{ route('user.balance.show') }}">Saldo</a></b>.</p>
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
