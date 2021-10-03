@extends('email.layout-en')

@section('email-content')
    @php
		$lang = 'it';
		$oldLang = \App::getLocale();
		\App::setLocale($lang);

		$deliveryDate = new DateTime($book->delivery_date);
    	$date_order = new DateTime($book->created_at);
    @endphp

    <p>Ciao <b>{{ $book->user->name }}</b>,</p>

    <p>grazie per aver ordinato con noi.</p>

    <p>Abbiamo inviato il tuo ordine a <b>{{ $book->seller->name }}</b>.</p>
	<br>

    <p>Di seguito i dettagli del servizio ordinato:</p>

    <p><b>Servizio:</b> {{ $book->service->name }}</p>

    <p><b>Ordine numero:</b> {{ $book->id }}</p>

    <p>
        <span class="datetime"><b>Data dell’ordine:</b> {{ $date_order->format('d-m-Y') }}</span>
        <br>
        <b>Orario:</b> {{ $date_order->format('H:i') }}
    </p>
	<br>

    <p><span class="datetime"><b>Data prevista per la consegna:</b> {{ $deliveryDate->format('d-m-Y') }}</span></p>
	<br>

    <p><b>Numero di servizi ordinati:</b> {{ $book->number_of_booking }}</p>
    <p><b>Numero di revisioni incluse:</b> {{ $book->online_revision == -1 ? trans('main.Unlimited revisions') : $book->online_revision }}</p>
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
        <p><b>Operazione pagamento n.</b> {{ $transaction_id }}</p>
    @endif
    <br><br>

    <p><b>Nota:</b></p>

    <p>
        @if (in_array($paid_type, [\App\Models\Book::PAID_PAYPAL, \App\Models\Book::PAID_CREDIT]))
            <b>{{ $book->seller->name }}</b> potrebbe richiedere file aggiuntivi o maggiori informazioni sul tuo servizio
            che potrai fornire tramite la <b><a href="{{ route('user.book.detail', ['id' => $book->id]) }}">pagina dell'ordine</a></b>. Alla data di consegna dovrai approvare l'ordine
            o, se necessario, richiedere ulteriori modifiche che dovranno essere approvate da
            <b>{{ $book->seller->name }}</b> e la data di consegna potrebbe cambiare.
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
