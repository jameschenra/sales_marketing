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

    <p>hai una nuova richiesta di prenotazione da confermare.</p>
    <br>

    <p>Di seguito i dettagli del servizio da confermare:</p>

    <p><b>Cliente:</b> {{ $book->user->full_name }}</p>

    <p><b>Servizio:</b> {{ $book->service->name }}</p>

    <p><b>Ordine n.</b> {{ $book->id }}</p>

    <p>
        <span class="datetime"><b>Data dell'ordine:</b> {{ $date_order->format('d-m-Y') }}</span>
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

    <p><b>Numero di servizi richiesti:</b> {{ $book->number_of_booking }}</p>
    <br>

    <p>
        @switch($paid_type)
            @case(\App\Models\Book::PAID_PAYPAL)
                <b>Prezzo pagato online con PayPal:</b> €{{ number_format($book->total_amount, 2) }}
                @break

            @case(\App\Models\Book::PAID_CREDIT)
                <b>Prezzo pagato online con il proprio credito:</b> €{{ number_format($book->total_amount, 2) }}
                @break

            @case(\App\Models\Book::PAID_OFFICE)
                <b>Prezzo da pagare il giorno dell'appuntamento:</b> €{{ number_format($book->total_amount, 2) }}
                @break

            @case(\App\Models\Book::PAID_FREE)
                <b>Prezzo pagato:</b> Servizio gratuito
                @break
        @endswitch
    </p>

    @isset($booking_transaction_id)
        <p><b>Operazione pagamento n.</b> {{ $booking_transaction_id }}</p>
    @endisset
    <br>

    <p>
        <b>Luogo dove fornire il servizio:</b>
        {{ $book->user_address ? trans('main.Customers Address') : $book->office->name }}
    </p>

    <p>
        <b>Indirizzo:</b>
        {{ $book->user_address ? trans('main.Address will be sent after confirmation') : $book->office->full_address }}
    </p>
    <br>

    @if (!empty($book->message))
        <p><b>Messaggio inviato durante la richiesta di prenotazione:</b><br>{{ $book->message }}</p>
        <br><br><br>
    @endif

    <p><b>{{ $book->seller->name }}</b> hai 48 ore per confermare la richiesta.</p>

    <p>Dopo la conferma riceverai i dati di contatto di <b>{{ $book->user->name }}</b>.</p>
    <br />

    <div class="text-align: center">
        <a href="{{ route('user.orders.view', ['id' => $book->id]) }}"><button class="view-btn btn-center">Vai su Ordini
                dei Clienti</button></a>
    </div>
    <br><br>

    <p><b>Nota:</b></p>

    <p>Ti ricordiamo che hai 48 ore per confermare la richiesta. Se non riesci a confermare entro le 48 ore, la prenotazione
        verrà automaticamente annullata e non verrà trattenuta nessuna fee. Se confermi la prenotazione, secondo i
        <b><a href="{{ route('user.terms') }}">Termini e Condizioni</a></b> sottoscritti, ti verrà trattenuta una fee per le spese di
        gestione dell'operazione.
    </p>
    <br><br>


    @php
        \App::setLocale($oldLang);
    @endphp
@stop
