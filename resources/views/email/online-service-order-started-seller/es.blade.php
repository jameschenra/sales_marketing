@extends('email.layout-en')

@section('email-content')
    @php
        $lang = "es";
        $oldLang = \App::getLocale();
        \App::setLocale($lang);

        $deliveryDate = new DateTime($book->delivery_date);
        $date_order = new DateTime($book->created_at);
    @endphp

    <p>Hola <b>{{ $book->seller->name }}</b>,</p>

    <p>tienes un nuevo pedido realizado con éxito.</p>
    <br>

    <p>A continuación los detalles del servicio ordenado:</p>

    <p><b>Cliente:</b> {{ $book->user->full_name }}</p>

    <p><b>Servicio:</b> {{ $book->service->name }}</p>

    <p><b>Número de pedido:</b> {{ $book->id }}</p>

    <p>
        <span class="datetime"><b>Fecha de pedido:</b> {{ $date_order->format('d-m-Y') }}</span>
        <br>
        <b>Horas:</b> {{ $date_order->format('H:i') }}
    </p>
    <br>

    <p><span class="datetime"><b>Fecha de entrega estimada:</b> {{ $deliveryDate->format('d-m-Y') }}</span></p>
    <br>

    <p><b>Número de servicios ordenados:</b> {{ $book->number_of_booking }}</p>
    <p><b>Número de revisiones incluidas:</b> {{ $book->online_revision == -1 ? trans('main.Unlimited revisions') : $book->online_revision }}</p>
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
    
    <p>Ve a ver el pedido realizado por {{ $book->user->name }}.</p>
    <br />

    <div class="text-align: center">
        <a href="{{ route('user.orders.view', ['id' => $book->id]) }}">
            <button class="view-btn btn-center">Ve a la página del pedido</button>
        </a>
    </div> 
    <br />

    <p><b>Nota:</b></p>

    <p>
        Puedes solicitar archivos adicionales o más información desde tu página de
        <b><a href="{{ route('user.orders.index') }}">Pedidos de Clientes</a></b>.
        En la fecha de entrega, <b>{{ $book->user->name }}</b> debe aprobar el pedido o, si es necesario,
        solicitar cambios adicionales que deben ser aprobados y, si es necesario, cambiar la fecha de entrega. Te recordamos que 4 (cuatro) días después del pedido confirmado, de acuerdo con los <b><a href="{{ route('user.terms') }}">Términos y Condiciones</a></b> firmado y en la verificación de la correcta ejecución de lo comprado por el cliente,
        podrás retirar las sumas adeudadas menos la tarifa que será retenido por el portal.
    </p>

    @php
        \App::setLocale($oldLang);
    @endphp
@stop
