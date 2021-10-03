@extends('email.layout-en')

@section('email-content')
    @php
        $lang = 'es';
        $oldLang = \App::getLocale();
        \App::setLocale($lang);

        $date_book = new DateTime($book->book_date);
        $date_order = new DateTime($book->created_at);
    @endphp

    <p>Hola <b>{{ $book->user->name }}</b>,</p>

    <p>gracias por reservar con nosotros.</p>

    <p>Hemos enviado tu solicitud de reserva a <b>{{ $book->seller->name }}</b>.</p>
    <br>

    <p>A continuación los detalles del servicio por confirmar:</p>

    <p><b>Servicio:</b> {{ $book->service->name }}</p>

    <p><b>Número de pedido:</b> {{ $book->id }}</p>

    <p>
        <span class="datetime"><b>Fecha de pedido:</b> {{ $date_order->format('d-m-Y') }}</span>
        <br>
        <b>Horas:</b> {{ $date_order->format('H:i') }}
    </p><br>

    @if (array_key_exists('time_period', unserialize($book->options)))
        <p>
            <span class="datetime"><b>Fecha de reserva:</b> {{ $date_book->format('d-m-Y') }}</span>
            <br><b>Horas:</b> de {{ \Carbon\Carbon::parse($book->book_date)->format('H:i') }}
            a
            <b>{{ \Carbon\Carbon::parse($book->book_date)->addMinute(unserialize($book->options)['time_period'])->format('H:i') }}</b>
        </p>
    @else
        <p>
            <span class="datetime"><b>Fecha de reserva:</b> {{ $date_book->format('d-m-Y') }}</span>
            <br><b>Horas:</b> {{ \Carbon\Carbon::parse($book->book_date)->format('H:i') }}
        </p>
    @endif

    <p><b>Duración:</b> {{ $book->prettyDuration }}</p>

    <p><b>Número de servicios solicitados:</b> {{ $book->number_of_booking }}</p>
    <br>

    <p>
        @switch($paid_type)
            @case(\App\Models\Book::PAID_PAYPAL)
                <b>Precio pagado con PayPal:</b> €{{ number_format($book->total_amount, 2) }}
                @break

            @case(\App\Models\Book::PAID_CREDIT)
                <b>Precio pagado con el saldo:</b> €{{ number_format($book->total_amount, 2) }}
                @break

            @case(\App\Models\Book::PAID_OFFICE)
                <b>Precio a pagar el día de la cita:</b> €{{ number_format($book->total_amount, 2) }}
                @break

            @case(\App\Models\Book::PAID_FREE)
                <b>Precio pagado:</b> Servicio gratuito
                @break
        @endswitch
    </p>

    @if (in_array($paid_type, [\App\Models\Book::PAID_PAYPAL, \App\Models\Book::PAID_CREDIT]))
        <p><b>Operación pago no.</b> {{ $transaction_id }}</p>
    @endif
    <br>

    <p>
        <b>Ubicación donde se proporciona el servicio:</b>
        {{ $book->user_address ? trans('main.Address buyer typed') : $book->office->city->name }}
    </p>

    <p>
        <b>Dirección:</b>
        {{ $book->user_address ? $book->user_address : trans('main.Address will be sent after confirmation') }}
    </p>

    <p>Con la confirmación recibirás los datos de contacto de <b>{{ $book->seller->name }}</b>.</p>
    <br><br>

    @if (!empty($book->message))
        <p><b>Mensaje que envió durante la solicitud de reserva:</b><br>{{ $book->message }}</p>
        <br><br><br>
    @endif

    <p><b>Nota:</b></p>

    <p>
        @if (in_array($paid_type, [\App\Models\Book::PAID_PAYPAL, \App\Models\Book::PAID_CREDIT]))
            Si no recibes la confirmación de <b>{{ $book->seller->name }}</b> dentro de las 48 horas, el servicio se
            cancelará automáticamente y se reembolsará el monto total. También te recordamos que el servicio reservado puede
            cancelarse y el monto total reembolsado hasta un máximo
            de 24 horas antes de la fecha y hora de la cita. Si cancelas dentro de las últimas 24 horas, de acuerdo con
            nuestros <b><a href="{{ route('user.terms') }}">Términos y
                    Condiciones</a></b>, se te cobrará una penalización del 50% sobre el monto pagado. El servicio se puede
            cancelar desde la página de
            <b><a href="{{ route('user.book') }}">Mis Compras</a></b>.
        @else
            Si no recibes la confirmación de <b>{{ $book->seller->name }}</b> dentro de las 48 horas, el servicio se
            cancelará automáticamente. También te recordamos que el servicio reservado puede cancelarse hasta un máximo de
            24 horas antes de la fecha y hora de la cita. El servicio se puede cancelar desde la página de
            <b><a href="{{ route('user.book') }}">Mis Compras</a></b>.
        @endif
    </p>

    @php
        \App::setLocale($oldLang);
    @endphp
@stop
