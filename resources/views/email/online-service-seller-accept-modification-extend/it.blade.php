@extends('email.layout-en')

@section('email-content')
    @php
        $lang = "en";
        $oldLang = \App::getLocale();
        \App::setLocale($lang);
    @endphp

    <p>Ciao <b>{{ $book->user->name }}</b>,</p>

    <p>La tua richiesta di apportare modifiche per il servizio <b>{{ $book->service->name }}</b>, ordine n.<b>{{ $book->id }}</b>, è stata accettata da <b>{{ $book->seller->name }}</b> e la data di consegna è stata spostata al <b>{{ $book->delivery_date_formatted }}</b>.</p>
    <br>
    
    <p>Vai alla pagina dell'ordine per leggere i motivi per cui {{ $book->seller->name }} ha effettuato il cambio della data di consegna.</p>
    <br>

    <div class="text-align: center">
        <a href="{{ route('user.book.detail', ['id' => $book->id]) }}">
            <button class="view-btn btn-center">Vai alla pagina dell'ordine</button>
        </a>
    </div> 
    <br><br>
    
    <p><b>Note:</b></p>
    
    <p>
        Secondo i <b><a href="{{ route('user.terms') }}">Termini e Condizioni</a></b> sottoscritti, quando vengono richieste modifiche, la data di consegna potrebbe variare. Per problemi riguardanti questo ordine e la relativa data di consegna puoi contattare la nostra Assistenza Clienti.
    </p>
    <br><br>

    @php
        \App::setLocale($oldLang);
    @endphp
@stop
