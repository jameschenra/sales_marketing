@extends('email.layout-en')

@section('email-content')
	@php
		$lang = 'it';
		$oldLang = \App::getLocale();
		\App::setLocale($lang);

		$date_book = new DateTime($book->book_date);
		$date_order = new DateTime($book->created_at);
	@endphp

    <p>Ciao <b>{{ $book->user->name }}</b>,</p>

    <p>il tuo appuntamento è stato cancellato con successo.</p>
	<br />

    <p> Di seguito i dettagli del servizio che hai cancellato:</p>

    <p><b>Servizio:</b> {{ $book->service->name }}</p>

    <p><b>Ordine numero:</b> {{ $book->id }}</p>

    <p>
		<span class="datetime"><b>Data dell’ordine:</b> {{ $date_order->format('d-m-Y') }}</span><br />
        <b>Orario:</b> {{ $date_order->format('H:i') }}
    </p>
    <br />

    <p>
		<span class="datetime"><b>Data della prenotazione:</b> {{ $date_book->format('d-m-Y') }}</span><br />
        <b>Orario:</b> {{ $date_book->format('H:i') }}
    </p>

    <p><b>Durata:</b> {{ $book->prettyDuration }}</p>

    <p><b>Numero di servizi che avevi prenotato:</b> {{ $book->number_of_booking }}</p>
    <br />

    <p>
        @if ($book->price > 0)
            @if ($book->is_paid_online == 1)
                <b>Prezzo rimborsato sul tuo conto:</b> €{{ number_format($refund_amount, 2) }}
            @else
                <b>Prezzo che dovevi pagare:</b> €{{ number_format($book->total_amount, 2) }}
            @endif
        @else
            <b>Prezzo pagato:</b> Servizio gratuito
        @endif
    </p>

    @if (!empty($bookingTransaction))
        <p><b>Operazione pagamento n.</b> {{ $bookingTransaction->id }}</p>
    @endif
    <br /><br />

    @if ($book->is_paid_online == 1 && $book->price > 0)
        <p>La somma che ti è stata rimborsata puoi utilizzarla ordinando o prenotando un nuovo servizio direttamente da
            <b><a href="{{ URL::route('user.auth.login') }}">qui</a></b>, oppure puoi prelevarla dalla pagina del tuo
            <b><a href="{{ route('user.balance.show') }}">Saldo</a></b>.
        </p>
    @else
        <p>
			Per effettuare una nuova prenotazione puoi accedere direttamente da
			<b><a href="{{ URL::route('user.auth.login') }}">qui</a></b>.
		</p>
    @endif

    @php
    	\App::setLocale($oldLang);
    @endphp
@stop
