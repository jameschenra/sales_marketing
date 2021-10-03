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

    <p>tu orden fue cancelado por {{ $book->seller->name }}</p>
	<br />

    <p>A continuación los detalles del pedido cancelado:</p>

    <p><b>Servicio:</b> {{ $book->service->name }}</p>

    <p><b>Número de pedido:</b> {{ $book->id }}</p>

    <p>
		<span class="datetime"><b>Fecha de pedido:</b> {{ $date_order->format('d-m-Y') }}</span><br />
        <b>Horas:</b> {{ $date_order->format('H:i') }}
    </p>
    <br />

    <p>
		<span class="datetime"><b>La fecha de entrega prevista era:</b> {{ date('d-m-Y', strtotime($book->delivery_date)) }}</span>
    </p>

    <p><b>Número de servicios que solicitó:</b> {{ $book->number_of_booking }}</p>
    <p><b>Número de revisiones incluidas:</b> {{ $book->online_revision == -1 ? trans('main.Unlimited revisions') : $book->online_revision }}</p>
    <br />

    <p>
        @if ($book->is_paid_online == 1 && $book->price > 0)
            <b>Precio reembolsado a tu cuenta:</b> €{{ number_format($book->total_amount, 2) }}
        @endif

        @if ($book->is_paid_online == 0 && $book->price > 0)
            <b>Precio que debería haber pagado:</b> €{{ number_format($book->total_amount, 2) }}
        @endif

        @if ($book->price == 0)
            <b>Precio pagado:</b> Servicio gratuito
        @endif
    </p>

    @if (!empty($bookingTransaction))
        <p><b>Operación pago no:</b> {{ $bookingTransaction->id }}</p>
    @endif
    <br />

    @if ($book->is_paid_online == 1 && $book->price > 0)
        <p>Puedes usar el monto reembolsado solicitando o reservando un nuevo servicio directamente desde
            <b><a href="{{ URL::route('user.auth.login') }}">aquí</a></b>, o puedes retirarlo desde tu página del 
			<b><a href="{{ route('user.balance.show') }}">Saldo</a></b>.
        </p>
    @else
        <p>Para hacer una nueva reserva puede iniciar sesión directamente desde 
			<b><a href="{{ URL::route('user.auth.login') }}">aquí</a></b>.</p>
    @endif

    @php
    	\App::setLocale($oldLang);
    @endphp
@stop
