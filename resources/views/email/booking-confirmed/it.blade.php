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

    <p>grazie per aver prenotato con noi.</p>

    <p>Il tuo appuntamento è stato confermato con successo.</p><br>

    <p>Di seguito i dettagli del servizio prenotato:</p>

    <p><b>Servizio:</b> {{ $book->service->name }}</p>

    <p><b>Ordine numero:</b> {{ $book->id }}</p>

    <p>
        <span class="datetime"><b>Data dell’ordine:</b> {{ $date_order->format('d-m-Y') }}</span>
        <br>
        <b>Orario:</b> {{ $date_order->format('H:i') }}
    </p>
	<br>

	@if (array_key_exists('time_period', unserialize($book->options)))
		<p>
			<span class="datetime"><b>Data della prenotazione:</b> {{ $date_book->format('d-m-Y') }}</span>
			<br><b>Orario:</b> dalle {{ \Carbon\Carbon::parse($book->book_date)->format('H:i') }}
			alle
			<b>{{ \Carbon\Carbon::parse($book->book_date)->addMinute(unserialize($book->options)['time_period'])->format('H:i') }}</b>
		</p>
	@else
		<p>
			<span class="datetime"><b>Data della prenotazione:</b> {{ $date_book->format('d-m-Y') }}</span>
			<br><b>Orario:</b> {{ \Carbon\Carbon::parse($book->book_date)->format('H:i') }}
		</p>
	@endif

    <p><b>Durata:</b> {{ $book->prettyDuration }}</p>

    <p><b>Numero di servizi prenotati:</b> {{ $book->number_of_booking }}</p>
    <br>

    <p>
        @switch($paid_type)
            @case(\App\Models\Book::PAID_PAYPAL)
                <b>Prezzo pagato con PayPal:</b> €{{ number_format($book->total_amount, 2) }}
            @break

            @case(\App\Models\Book::PAID_CREDIT)
                <b>Prezzo pagato con il proprio saldo:</b> €{{ number_format($book->total_amount, 2) }}
            @break

            @case(\App\Models\Book::PAID_OFFICE)
                <b>Prezzo da pagare il giorno dell’appuntamento:</b> €{{ number_format($book->total_amount, 2) }}
            @break

            @case(\App\Models\Book::PAID_FREE)
                <b>Prezzo pagato:</b> Servizio gratuito
            @break
        @endswitch
    </p>

    @if (in_array($paid_type, [\App\Models\Book::PAID_PAYPAL, \App\Models\Book::PAID_CREDIT]))
        <p>
            <b>Operazione pagamento n.</b> {{ $transaction_id }}
        </p>
    @endif
    <br>

    <p>
        <b>Luogo dove viene fornito il servizio:</b>
        {{ $book->user_address ? trans('main.Address buyer typed') : $book->office->city->name }}
    </p>

    <p>
        <b>Indirizzo:</b>
        {{ $book->user_address ? $book->user_address : $book->office->full_address }}
    </p>

    <p>
        <b>Telefono della sede:</b> {{ $book->office->phone_number }}
    </p>
	<br>

    <p><b>Dati di contatto di:</b> {{ $book->seller->full_name }}</p>

    <p><b>Telefono:</b> {{ $book->seller->phone }}</p>

    <p><b>Email:</b> {{ $book->seller->email }}</p>
	<br>

    @if (!empty($book->message))
        <p><b>Messaggio che hai inviato durante la prenotazione:</b><br>{{ $book->message }}</p>
		<br><br><br>
    @endif

    <p><b>Nota:</b></p>

    <p>
        @if (in_array($paid_type, [\App\Models\Book::PAID_PAYPAL, \App\Models\Book::PAID_CREDIT]))
            Ti ricordiamo che il servizio prenotato può essere cancellato e l'intero importo rimborsato fino a un massimo
            di 24 ore prima della data e dell'orario dell'appuntamento. Se cancelli entro le ultime 24 ore, secondo i <b><a href="{{ route('user.terms') }}">Termini e Condizioni</a></b> sottoscritti,
			ti sarà applicata una penale del 50% sull'importo pagato. Il servizio si può cancellare dalla pagina
            <b><a href="{{ route('user.book') }}">I miei acquisti</a></b>.
        @else
            Ti ricordiamo che il servizio prenotato può essere cancellato fino a un massimo di 24 ore prima della data e
            dell'orario dell'appuntamento. Il servizio si può cancellare dalla pagina
            <b><a href="{{ route('user.book') }}">I miei acquisti</a></b>.
        @endif
    </p>

    @php
    \App::setLocale($oldLang);
    @endphp
@stop
