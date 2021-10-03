@extends('email.layout-en')

@section('email-content')
    @php
        $lang = "es";
        $oldLang = \App::getLocale();
        \App::setLocale($lang);
    @endphp

    <p>Hola <b>{{ $book->seller->name }}</b>,</p>
    
    <p><b>{{ $book->user->name }}</b> aceptó tu solicitud para extender la fecha de entrega de su servicio <b>{{ $book->service->name }}</b>, orden n.<b>{{ $book->id }}</b>.</p>
    <br/>

    <p>La nueva fecha de entrega será el {{ $new_delivery_date }}.</p>
    <br/>

    @php
        \App::setLocale($oldLang);
    @endphp
@stop
