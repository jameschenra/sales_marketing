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

    <p>Abbiamo inviato la tua richiesta di prenotazione a <b>{{ $book->seller->name }}</b>.</p>
    <br>

    <p>Di seguito i dettagli del servizio da confermare:</p>

    <p><b>Servizio:</b> {{ $book->service->name }}</p>

    <p><b>Ordine n.</b> {{ $book->id }}</p>

    <p>
        <span class="datetime"><b>Data dell'ordine:</b> {{ $date_order->format('d-m-Y') }}</span>
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

    @if (in_array($paid_type, [\App\Models\Book::PAID_PAYPAL, \App\Models\Book::PAID_CREDIT]))
        <p><b>Operazione pagamento n.</b> {{ $transaction_id }}</p>
    @endif
    <br>

    <p>
        <b>Luogo dove viene fornito il servizio:</b>
        {{ $book->user_address ? trans('main.Address buyer typed') : $book->office->city->name }}
    </p>

    <p>
        <b>Indirizzo:</b>
        {{ $book->user_address ? $book->user_address : trans('main.Address will be sent after confirmation') }}
    </p>

    <p>Con la conferma riceverai i dati di contatto di <b>{{ $book->seller->name }}</b>.</p>
    <br><br>

    @if (!empty($book->message))
        <p><b>Messaggio che hai inviato durante la richiesta di prenotazione:</b><br>{{ $book->message }}</p>
        <br><br><br>
    @endif

    <p><b>Nota:</b></p>

    <p>
        @if (in_array($paid_type, [\App\Models\Book::PAID_PAYPAL, \App\Models\Book::PAID_CREDIT]))
            Se non ricevi la conferma da <b>{{ $book->seller->name }}</b> entro le 48 ore il servizio verrà
            automaticamente annullato e l'intero importo ti verrà rimborsato. Ti ricordiamo anche che, il servizio prenotato
            può essere cancellato e l'intero importo rimborsato fino a un massimo
            di 24 ore prima della data e dell'orario dell'appuntamento. Se cancelli entro le ultime 24 ore, secondo i nostri
            <b><a href="{{ route('user.terms') }}">Termini e
                    Condizioni</a></b>, ti sarà applicata una penale del 50% sull'importo pagato. Il servizio si può
            cancellare dalla pagina
            <b><a href="{{ route('user.book') }}">I miei acquisti</a></b>.
        @else
            Se non ricevi la conferma da <b>{{ $book->seller->name }}</b> entro le 48 ore il servizio verrà
            automaticamente annullato. Ti ricordiamo anche che, il servizio prenotato può essere cancellato fino a un
            massimo di 24 ore prima della data e
            dell'orario dell'appuntamento. Il servizio si può cancellare dalla pagina
            <b><a href="{{ route('user.book') }}">I miei acquisti</a></b>.
        @endif
    </p>

    @php
        \App::setLocale($oldLang);
    @endphp
@stop
