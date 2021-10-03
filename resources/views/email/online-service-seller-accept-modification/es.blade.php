@extends('email.layout-en')

@section('email-content')
    @php
        $lang = "en";
        $oldLang = \App::getLocale();
        \App::setLocale($lang);
    @endphp

    <p>Hola <b>{{ $book->user->name }}</b>,</p>

    <p>tu solicitud para realizar cambios por el servicio <b>{{ $book->service->name }}</b>, orden n.<b>{{ $book->id }}</b>,  ha sido aceptada por <b>{{ $book->seller->name }}</b></p>
    <p>Recibirás tu servicio dentro de la fecha prevista de entrega.</p>
    <br><br>
    @php
        \App::setLocale($oldLang);
    @endphp
@stop
