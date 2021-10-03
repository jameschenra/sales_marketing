@extends('email.layout-en')

@section('email-content')
    @php
		$lang = 'es';
		$oldLang = \App::getLocale();
		\App::setLocale($lang);

		$date_book = new DateTime($book->book_date);
		$date_order = new DateTime($book->created_at);
    @endphp

    <p>Hola <b>{{ $book->seller->name }}</b>,</p>

    <p>has cancelado con éxito la cita con {{ $book->user->name }}</p>
    <br>

    <p>A continuación los detalles del servicio cancelado:</p>

    <p><b>Cliente:</b> {{ $book->user->full_name }}</p>

    <p><b>Servicio:</b> {{ $book->service->name }}</p>

    <p><b>Número de pedido:</b> {{ $book->id }}</p>

    <p>
		<span class="datetime"><b>Fecha de pedido:</b> {{ $date_order->format('d-m-Y') }}</span><br />
        <b>Horas:</b> {{ $date_order->format('H:i') }}
    </p>
    <br />

    <p>
		<span class="datetime"><b>Fecha de reserva:</b> {{ $date_book->format('d-m-Y') }}</span><br />
        <b>Horas:</b> {{ $date_book->format('H:i') }}
    </p>

    <p><b>Duración:</b> {{ $book->prettyDuration }}</p>

    <p><b>Número de servicios reservados:</b> {{ $book->number_of_booking }}</p>
    <br />

    <p>
        @if ($book->is_paid_online == 1 && $book->price > 0)
            <b>Precio reembolsado al cliente:</b> €{{ number_format($book->total_amount, 2) }}
        @endif

        @if ($book->is_paid_online == 0 && $book->price > 0)
            <b>Precio que el cliente deberia haber pagado:</b> €{{ number_format($book->total_amount, 2) }}
        @endif

        @if ($book->price == 0)
            <b>Precio pagado:</b> Servicio gratuito
        @endif
    </p>

    @if (!empty($bookingTransaction))
        <p><b>Operación pago no:</b> {{ $bookingTransaction->id }}</p>
    @endif

    @if (!empty($feeTransaction))
        <p><b>Operación pago de cuota no:</b> {{ $feeTransaction->id }}</p>
    @endif
    <br /><br />

    @php
    	\App::setLocale($oldLang);
    @endphp
@stop
