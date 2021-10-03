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

    <p>l'appuntamento con {{ $book->user->name }} è stato cancellato perché non hai confermato entro i termini.</p>
    <br />

    <p>Di seguito i dettagli del servizio cancellato:</p>

    <p><b>Cliente:</b> {{ $book->user->full_name }}</p>

    <p><b>Servizio:</b> {{ $book->service->name }}</p>

    <p><b>Ordine numero:</b> {{ $book->id }}</p>

	<p>
        <span class="datetime"><b>Data dell’ordine:</b> {{ $date_order->format('d-m-Y') }}</span>
        <br>
        <b>Orario:</b> {{ $date_order->format('H:i') }}
    </p>
    <br>

    <p>
        <span class="datetime"><b>Data della prenotazione:</b> {{ $date_book->format('d-m-Y') }}</span>
        <br>
        <b>Orario:</b> {{ $date_book->format('H:i') }}
    </p>

    <p><b>Durata:</b> {{ $book->prettyDuration }}</p>

    <p><b>Numero di servizi prenotati:</b> {{ $book->number_of_booking }}</p>
    <br>

    <p>
        @if ($book->is_paid_online == 1 && $book->price > 0)
            <b>Prezzo che hai rimborsato:</b> €{{ number_format($book->total_amount, 2) }}
        @endif

        @if ($book->is_paid_online == 0 && $book->price > 0)
            <b>Prezzo che il cliente doveva pagare:</b> €{{ number_format($book->total_amount, 2) }}
        @endif

        @if ($book->price == 0)
            <b>Prezzo pagato:</b> Servizio gratuito
        @endif
    </p>

    @if (!empty($bookingTransaction))
        <p><b>Operazione pagamento n.</b> {{ $bookingTransaction->id }}</p>
    @endif

    @if (!empty($feeTransaction))
        <p><b>Operazione pagamento fee n.</b> {{ $feeTransaction->id }}</p>
    @endif
    <br><br>

    @php
    	\App::setLocale($oldLang);
    @endphp
@stop
