@extends('email.layout-en')

@section('email-content')
    @php
        $lang = "it";
        $oldLang = \App::getLocale();
        \App::setLocale($lang);
    @endphp

    <p>Ciao <b>{{ $book->seller->name }}</b>,</p>

    <p><b>{{ $book->user->name }}</b> non ha accettato la tua richiesta ad estendere la data di consegna del suo servizio <b>{{ $book->service->name }}</b>, ordine n.<b>{{ $book->id }}</b>.</p>
    <br />

    <p><b>Motivo per cui non è stata accettata: </b> {{ $cancel_reason }}</p>
    <br /><br />

    <p>Vai alla pagina dell'ordine e prova inserendo una data diversa oppure invia un messaggio.</p>
    <br />

    <div class="text-align: center">
        <a href="{{ route('user.orders.view', ['id' => $book->id]) }}">
            <button class="view-btn btn-center">Vai alla pagina dell'ordine</button>
        </a>
    </div>  
    <br />

    @php
    \App::setLocale($oldLang);
    @endphp
@stop
