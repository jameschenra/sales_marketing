@extends('email.layout-en')

@section('email-content')
    @php
        $lang = "en";
        $oldLang = \App::getLocale();
        \App::setLocale($lang);
    @endphp

    <p>Ciao <b>{{ $book->user->name }}</b>,</p>

    <p>la tua richiesta di apportare modifiche per il servizio <b>{{ $book->service->name }}</b>, ordine n.<b>{{ $book->id }}</b>, è stata accettata da <b>{{ $book->seller->name }}</b></p>
    <p>Riceverai il tuo servizio entra la data di consegna prevista.</p>
    <br><br>
    @php
        \App::setLocale($oldLang);
    @endphp
@stop
