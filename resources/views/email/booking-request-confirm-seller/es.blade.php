@extends('email.layout-en')

@section('email-content')
    @php
        $lang = 'es';
        $oldLang = \App::getLocale();
        \App::setLocale($lang);

        $date_book = new DateTime($book->book_date);
        $date_order = new DateTime($book->created_at);
    @endphp

    <p>Hello <b>{{ $book->seller->name }}</b>,</p>

    <p>tienes una nueva solicitud de reserva por confirmar.</p>
    <br>

    <p>A continuación los detalles del servicio por confirmar:</p>

    <p><b>Cliente:</b> {{ $book->user->full_name }}</p>

    <p><b>Servicio:</b> {{ $book->service->name }}</p>

    <p><b>Número de pedido:</b> {{ $book->id }}</p>

    <p>
        <span class="datetime"><b>Fecha de pedido:</b> {{ $date_order->format('d-m-Y') }}</span>
        <br>
        <b>Horas:</b> {{ $date_order->format('H:i') }}
    </p>
    <br>

    <p>
        <span class="datetime"><b>Fecha de reserva:</b> {{ $date_book->format('d-m-Y') }}</span>
        <br>
        <b>Horas:</b> {{ $date_book->format('H:i') }}
    </p>

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

    @isset($booking_transaction_id)
        <p><b>Operación pago no.</b> {{ $booking_transaction_id }}</p>
    @endisset
    <br>

    <p>
        <b>Ubicación donde se proporciona el servicio:</b>
        {{ $book->user_address ? trans('main.Customers Address') : $book->office->name }}
    </p>

    <p>
        <b>Dirección:</b>
        {{ $book->user_address ? trans('main.Address will be sent after confirmation') : $book->office->full_address }}
    </p>
    <br>

    @if (!empty($book->message))
        <p><b>Mensaje enviado durante la solicitud de reserva:</b><br>{{ $book->message }}</p>
        <br><br><br>
    @endif

    <p><b>{{ $book->seller->name }}</b> tienes 48 horas para confirmar la solicitud.</p>

    <p>Después de la confirmación recibirás los datos de contacto de <b>{{ $book->user->name }}</b>.</p>
    <br />

    <div class="text-align: center">
        <a href="{{ route('user.orders.view', ['id' => $book->id]) }}"><button class="view-btn btn-center">Vaya a Pedidos
                de Clientes</button></a>
    </div>
    <br><br>

    <p><b>Nota:</b></p>

    <p>Recuerda que tienes 48 horas para confirmar la solicitud. Si no puedes confirmar dentro de las 48 horas posteriores a
        la fecha del pedido realizado, la reserva se cancelará automáticamente y no se retendrá ninguna tarifa. Si confirmas
        la reserva, de acuerdo con los <b><a href="{{ route('user.terms') }}">Términos y Condiciones</a></b>
        firmado se te retendrá una tarifa por los gastos de gestión.
    </p>
    <br><br>

    @php
        \App::setLocale($oldLang);
    @endphp
@stop
