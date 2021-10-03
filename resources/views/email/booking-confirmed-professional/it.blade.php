@extends('email.layout-en')

@section('email-content')
    @php
        $lang = "it";
        $oldLang = \App::getLocale();
        \App::setLocale($lang);

        $date_book = new DateTime($book->book_date);
        $date_order = new DateTime($book->created_at);
    @endphp

    <p>Ciao <b>{{ $book->seller->name }}</b>,</p>

    <p>hai un nuovo appuntamento confermato con successo.</p>
    <br>

    <p>Di seguito i dettagli del servizio prenotato:</p>

    <p><b>Cliente:</b> {{ $book->user->full_name }}</p>

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

    @isset ($booking_transaction_id)
        <p><b>Operazione pagamento n.</b> {{ $booking_transaction_id }}</p>
    @endisset
    
    @isset ($fee_transaction_id)
        <p><b>Operazione pagamento fee n.</b> {{ $fee_transaction_id }}</p>
    @endisset
    <br>

    <p>
        <b>Luogo dove fornire il servizio:</b>
        {{ $book->user_address ? trans('main.Customers Address') : $book->office->name }}
    </p>

    <p>
        <b>Indirizzo:</b>
        {{ $book->user_address ? $book->user_address : $book->office->full_address }}
    </p>

    <p><b>Telefono della sede:</b> {{ $book->office->phone_number }}</p>
    <br>

    <p><b>Dati di contatto di</b> {{ $book->user->full_name }}</p>

    <p><b>Telefono:</b> {{ $book->user->phone }}</p>

    <p><b>Email:</b> {{ $book->user->email }}</p>
    <br>

    @if (!empty($book->message))
        <p><b>Messaggio inviato durante la prenotazione:</b><br>{{ $book->message }}</p>
        <br><br><br>
    @endif

    <p><b>Nota:</b></p>

    <p>
        Ti ricordiamo che 4 (quattro) giorni dopo la data dell'appuntamento confermato, secondo i
        <b><a href="{{ route('user.terms') }}">Termini e Condizioni</a></b>
        sottoscritti e nel controllo della corretta esecuzione di quanto acquistato dal cliente,
        potrai prelevare le somme a te spettanti meno la fee che sarà trattenuta dal portale.
        Qualora non ti sarà possibile fornire il servizio richiesto,
        potrai cancellare la prenotazione dalla tua pagina
        <b><a href="{{ route('user.orders.index') }}">Ordini dei Clienti</a></b>
        fino a un massimo di 24 ore prima della data e dell'orario dell'appuntamento.
        Nel caso tu decida di annullare il servizio richiesto,
        le somme versate dal cliente saranno rimborsate a quest'ultimo direttamente dal portale e ti sarà trattenuta una fee per le spese di gestione dell'operazione.
    </p>

    @php
        \App::setLocale($oldLang);
    @endphp
@stop
