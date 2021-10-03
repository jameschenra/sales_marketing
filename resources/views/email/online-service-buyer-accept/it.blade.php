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

    <p>il servizio è stato accettato e confermato da <b>{{ $book->user->name }}</b></p>
    <br>

    <p>Di seguito i dettagli del servizio ordinato:</p>

    <p><b>Cliente:</b> {{ $book->user->full_name }}</p>

    <p><b>Servizio:</b> {{ $book->service->name }}</p>

    <p><b>Ordine numero:</b> {{ $book->id }}</p>

    <p>
        <span class="datetime"><b>Data dell'ordine:</b> {{ $date_order->format('d-m-Y') }}</span>
        <br>
        <b>Orario:</b> {{ $date_order->format('H:i') }}
    </p>
    <br>

    <p><span class="datetime"><b>Data della consegna:</b> {{ $deliveryDate->format('d-m-Y') }}</span></p>

    <p><b>Numero di servizi ordinati:</b> {{ $book->number_of_booking }}</p>
    <p><b>Numero di revisioni incluse:</b> {{ $book->online_revision == -1 ? trans('main.Unlimited revisions') : $book->online_revision }}</p>
    <br> <br>

    <p>
        @switch($book->payment_type)
            @case(\App\Models\Book::PAID_PAYPAL)
            <b>Prezzo pagato online con PayPal:</b> €{{ number_format($book->total_amount, 2) }}
            @break

            @case(\App\Models\Book::PAID_CREDIT)
            <b>Prezzo pagato con il proprio credito:</b> €{{ number_format($book->total_amount, 2) }}
            @break

            @case(\App\Models\Book::PAID_OFFICE)
            <b>Prezzo da pagare il giorno della consegna:</b> €{{ number_format($book->total_amount, 2) }}
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
    <br><br>

    @php
        \App::setLocale($oldLang);
    @endphp
@stop
