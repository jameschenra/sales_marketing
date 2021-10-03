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

    <p>el servicio ha sido aceptado y confirmado por <b>{{ $book->user->name }}</b> </p>
    <br>

    <p>A continuación los detalles del servicio solicitado:</p>

    <p><b>Cliente:</b> {{ $book->user->full_name }}</p>

    <p><b>Servicio:</b> {{ $book->service->name }}</p>

    <p><b>Número de pedido:</b> {{ $book->id }}</p>

    <p>
        <span class="datetime"><b>Date of order:</b> {{ $date_order->format('d-m-Y') }}</span>
        <br>
        <b>Horas:</b> {{ $date_order->format('H:i') }}
    </p>
    <br>

    <p><span class="datetime"><b>Fecha de entrega:</b> {{ $deliveryDate->format('d-m-Y') }}</span></p>

    <p><b>Número de servicios solicitados:</b> {{ $book->number_of_booking }}</p>
    <p><b>Número de revisiones incluidas:</b> {{ $book->online_revision == -1 ? trans('main.Unlimited revisions') : $book->online_revision }}</p>    
    <br> <br>

    <p>
        @switch($book->payment_type)
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
    <br><br>

    @php
        \App::setLocale($oldLang);
    @endphp
@stop
