@extends('email.layout-en')

@section('email-content')
	@php
		$lang = 'it';
		$oldLang = \App::getLocale();
		\App::setLocale($lang);

		$date_book = new DateTime($book->book_date);
		$date_order = new DateTime($book->created_at);
	@endphp

    <p>Ciao <b>{{ $book->seller->name }}</b>,</p>

    <p><b>{{ $book->user->name }}</b> ha cancellato il suo appuntamento.</p>
	<br />

    <p>Di seguito i dettagli del servizio che ha cancellato:</p>

    <p><b>Cliente:</b> {{ $book->user->full_name }}</p>

    <p><b>Servizio:</b> {{ $book->service->name }}</p>

    <p><b>Ordine numero:</b> {{ $book->id }}</p>

    <p>
		<span class="datetime"><b>Data dell’ordine:</b> {{ $date_order->format('d-m-Y') }}</span><br />
        <b>Orario:</b> {{ $date_order->format('H:i') }}
    </p>
    <br />

    <p><span class="datetime"><b>Data della prenotazione:</b> {{ $date_book->format('d-m-Y') }}</span><br />
        <b>Orario:</b> {{ $date_book->format('H:i') }}
    </p>

    <p><b>Durata:</b> {{ $book->prettyDuration }}</p>

    <p><b>Numero di servizi prenotati:</b> {{ $book->number_of_booking }}</p>
    <br />

    <p>
        @if ($book->price > 0)
            @if ($book->is_paid_online == 1)
                <b>Prezzo che hai rimborsato:</b> €{{ number_format($refund_amount, 2) }}
            @else
                <b>Prezzo che il cliente doveva pagare:</b> €{{ number_format($book->total_amount, 2) }}
            @endif
        @else
            <b>Prezzo pagato:</b> Servizio gratuito
        @endif
    </p>

    @if (!empty($bookingTransaction))
        <p><b>Operazione pagamento n.</b> {{ $bookingTransaction->id }}</p>
    @endif

    @if (!empty($feeTransaction))
        <p><b>Operazione pagamento fee n.</b> {{ $feeTransaction->id }}</p>
    @endif
    <br /><br />

    @php
    	\App::setLocale($oldLang);
    @endphp
@stop
