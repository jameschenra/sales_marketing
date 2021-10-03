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

    <p>il tuo ordine è stato cancellato da {{ $book->seller->name }}</p>
	<br />

    <p> Di seguito i dettagli dell'ordine cancellato:</p>

    <p><b>Servizio:</b> {{ $book->service->name }}</p>

    <p><b>Ordine numero:</b> {{ $book->id }}</p>

    <p>
		<span class="datetime"><b>Data dell’ordine:</b> {{ $date_order->format('d-m-Y') }}</span><br />
        <b>Orario:</b> {{ $date_order->format('H:i') }}
    </p>
    <br />

    <p>
		<span class="datetime"><b>La data prevista per la consegna era il:</b> {{ date('d-m-Y', strtotime($book->delivery_date)) }}</span>
    </p>

    <p><b>Numero di servizi che avevi ordinato:</b> {{ $book->number_of_booking }}</p>
    <p><b>Numero di revisioni incluse:</b> {{ $book->online_revision == -1 ? trans('main.Unlimited revisions') : $book->online_revision }}</p>
    <br />

    <p>
        @if ($book->is_paid_online == 1 && $book->price > 0)
            <b>Prezzo rimborsato sul tuo conto:</b> €{{ number_format($book->total_amount, 2) }}
        @endif

        @if ($book->is_paid_online == 0 && $book->price > 0)
            <b>Prezzo che dovevi pagare:</b> €{{ number_format($book->total_amount, 2) }}
        @endif

        @if ($book->price == 0)
            <b>Prezzo pagato:</b> Servizio gratuito
        @endif
    </p>

    @if (!empty($bookingTransaction))
        <p><b>Operazione pagamento n.</b> {{ $bookingTransaction->id }}</p>
    @endif
    <br />

    @if ($book->is_paid_online == 1 && $book->price > 0)
        <p>
			La somma che ti è stata rimborsata puoi utilizzarla ordinando o prenotando un nuovo servizio direttamente da
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
