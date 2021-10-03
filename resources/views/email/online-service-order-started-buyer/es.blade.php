@extends('email.layout-en')

@section('email-content')
    @php
		$lang = 'es';
		$oldLang = \App::getLocale();
		\App::setLocale($lang);

		$deliveryDate = new DateTime($book->delivery_date);
		$date_order = new DateTime($book->created_at);
    @endphp

    <p>Hola <b>{{ $book->user->name }}</b>,</p>

    <p>gracias por ordenar con nosotros.</p>

    <p>Hemos enviado tu pedido a <b>{{ $book->seller->name }}</b>.</p>
	<br>

    <p>A continuación los detalles del servicio solicitado:</p>

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
    
	<p><b>Número de servicios solicitados:</b> {{ $book->number_of_booking }}</p>
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

    @if (in_array($paid_type, [\App\Models\Book::PAID_PAYPAL, \App\Models\Book::PAID_CREDIT]))
        <p><b>Operación pago no.</b> {{ $transaction_id }}</p>
    @endif
    <br><br>

    <p><b>Nota:</b></p>

    <p>
        @if (in_array($paid_type, [\App\Models\Book::PAID_PAYPAL, \App\Models\Book::PAID_CREDIT]))
            <b>{{ $book->seller->name }}</b> puede requerir archivos adicionales o más información sobre tu servicio que
            podras enviar tras de <b><a href="{{ route('user.book.detail', ['id' => $book->id]) }}">la página del pedido</a></b>. En la fecha de entrega, deberá aprobar el pedido o,
            si es necesario, solicitar cambios adicionales que tendrán que ser aprobados por
            <b>{{ $book->seller->name }}</b> y la fecha de entrega puede variar.
        @else
            Te recordamos que el servicio reservado puede cancelarse hasta un máximo de 24 horas antes de la fecha
            y hora de la cita. El servicio se puede cancelar desde la página de
            <b><a href="{{ route('user.book') }}">Mis Compras</a></b>.
        @endif
    </p>

    @php
    	\App::setLocale($oldLang);
    @endphp
@stop
