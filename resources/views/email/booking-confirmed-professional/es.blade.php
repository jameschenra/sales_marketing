@extends('email.layout-en')

@section('email-content')
    @php
        $lang = "es";
        $oldLang = \App::getLocale();
        \App::setLocale($lang);

        $date_book = new DateTime($book->book_date);
        $date_order = new DateTime($book->created_at);
    @endphp


    <p>Hola <b>{{ $book->seller->name }}</b>,</p>

    <p>tienes una nueva cita confirmada con éxito.</p>
    <br>

    <p>A continuación los detalles del servicio reservado:</p>

    <p><b>Cliente:</b> {{ $book->user->full_name }}</p>

    <p><b>Servicio:</b> {{ $book->service->name }}</p>

    <p><b>Número de pedido:</b> {{ $book->id }}</p>

    <p>
        <span class="datetime"><b>Fecha de pedido:</b> {{ $date_order->format('d-m-Y') }}</span>
        <br>
        <b>Horas:</b> {{ $date_order->format('H:i') }}
    </p>
    <br>

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

    <p><b>Número de servicios reservados:</b> {{ $book->number_of_booking }}</p>
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

    @isset ($booking_transaction_id)
        <p><b>Operación pago no.</b> {{ $booking_transaction_id }}</p>
    @endisset
    
    @isset ($fee_transaction_id)
        <p><b>Operación pago de cuota n.</b> {{ $fee_transaction_id }}</p>
    @endisset
    <br>

    <p>
        <b>Ubicación donde se proporciona el servicio:</b>
        {{ $book->user_address ? trans('main.Customers Address') : $book->office->name }}
    </p>

    <p>
        <b>Dirección:</b>
        {{ $book->user_address ? $book->user_address : $book->office->full_address }}
    </p>

    <p><b>Teléfono:</b> {{ $book->office->phone_number }}</p>
    <br>

    <p><b>Datos de contacto de</b> {{ $book->user->full_name }}</p>

    <p><b>Teléfono:</b> {{ $book->user->phone }}</p>

    <p><b>Correo electrónico:</b> {{ $book->user->email }}</p>
    <br>

    @if (!empty($book->message))
        <p><b>Mensaje enviado durante la reserva:</b><br>{{ $book->message }}</p>
        <br><br><br>
    @endif

    <p><b>Nota:</b></p>

    <p>
        Te recordamos que 4 (cuatro) días después de la fecha de la cita confirmada, de acuerdo con los <b><a href="{{ route('user.terms') }}">Términos y Condiciones</a></b> firmado y en la verificación de la correcta ejecución de lo comprado por el cliente,
        podrás retirar las sumas adeudadas menos la tarifa que será retenido por el portal.
        Si no puedes proporcionar el servicio solicitado, puedes cancelar la reserva desde tu página de
        <b><a href="{{ route('user.orders.index') }}">Pedidos de Clientes</a></b>
        hasta un máximo de 24 horas antes de la fecha y hora de la cita.
        En caso de que decidas cancelar el servicio solicitado,
        las sumas abonadas por el cliente serán reembolsadas a este último directamente desde el portal y se retendrá una tarifa por los gastos de gestión de la operación.
    </p>

    @php
        \App::setLocale($oldLang);
    @endphp
@stop
