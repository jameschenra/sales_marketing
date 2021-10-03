@extends('email.layout-en')

@section('email-content')
    @php
        $lang = "it";
        $oldLang = \App::getLocale();
        \App::setLocale($lang);
    @endphp

    <p>Ciao <b>{{ $book->seller->name }}</b>,</p>

    <p>
        <b>{{ $book->user->name }}</b> non ha risposto alla tua richiesta per estendere la data di consegna del suo servizio <b>{{ $book->service->name }}</b>, ordine n.<b>{{ $book->id }}</b> entro le 24 ore e, secondo i <b><a href="{{ route('user.terms') }}">Termini e Condizioni</a></b> sottoscritti,
        la nuova data di consegna è stata automaticamente confermata per il giorno {{ $new_delivery_date }}.
    </p>
    <br/> <br/>

    @php
        \App::setLocale($oldLang);
    @endphp
@stop
