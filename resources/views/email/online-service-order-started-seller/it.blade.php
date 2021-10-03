@extends('email.layout-en')

@section('email-content')
    @php
        $lang = "it";
        $oldLang = \App::getLocale();
        \App::setLocale($lang);

        $deliveryDate = new DateTime($book->delivery_date);
        $date_order = new DateTime($book->created_at);
    @endphp

    <p>Ciao <b>{{ $book->seller->name }}</b>,</p>

    <p>hai un nuovo ordine effettuato con successo.</p>
    <br>

    <p>Di seguito i dettagli del servizio ordinato:</p>

    <p><b>Cliente:</b> {{ $book->user->full_name }}</p>

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

    @isset ($booking_transaction_id)
        <p><b>Operazione pagamento n.</b> {{ $booking_transaction_id }}</p>
    @endisset
    
    @isset ($fee_transaction_id)
        <p><b>Operazione pagamento fee n.</b> {{ $fee_transaction_id }}</p>
    @endisset
    <br>

    <p>Vai a vedere l'ordine effettuato da {{ $book->user->name }}.</p>
    <br />

    <div class="text-align: center">
        <a href="{{ route('user.orders.view', ['id' => $book->id]) }}">
            <button class="view-btn btn-center">Vai alla pagina dell'ordine</button>
        </a>
    </div> 
    <br />

    <p><b>Nota:</b></p>

    <p>
        Puoi richiedere file aggiuntivi o maggiori informazioni dalla tua pagina 
        <b><a href="{{ route('user.orders.index') }}">Ordini dei Clienti</a></b>. 
        Alla data di consegna <b>{{ $book->user->name }}</b> deve approvare l'ordine o, 
        se necessario, richiedere ulteriori modifiche che devono essere da te approvate e, se necessario, modificare la data di consegna. Ti ricordiamo che 4 (quattro) giorni dopo la data dell'ordine confermato, secondo i
        <b><a href="{{ route('user.terms') }}">Termini e Condizioni</a></b>
        sottoscritti e nel controllo della corretta esecuzione di quanto acquistato dal cliente,
        potrai prelevare le somme a te spettanti meno la fee che sarà trattenuta dal portale.
     </p>

    @php
        \App::setLocale($oldLang);
    @endphp
@stop
