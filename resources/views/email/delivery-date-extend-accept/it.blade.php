@extends('email.layout-en')

@section('email-content')
    @php
        $lang = "it";
        $oldLang = \App::getLocale();
        \App::setLocale($lang);
    @endphp

    <p>Ciao <b>{{ $book->seller->name }}</b>,</p>
    
    <p><b>{{ $book->user->name }}</b> ha accettato la tua richiesta ad estendere la data di consegna del suo servizio <b>{{ $book->service->name }}</b>, ordine n.<b>{{ $book->id }}</b>.</p>
    <br/>

    <p>La nuova data di consegna sarà il {{ $new_delivery_date }}.</p>
    <br/>

    @php
        \App::setLocale($oldLang);
    @endphp
@stop
