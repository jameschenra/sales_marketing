@extends('email.layout-en')

@section('email-content')
    @php
        $lang = "es";
        $oldLang = \App::getLocale();
        \App::setLocale($lang);
    @endphp

    <p>Hola <b>{{ $book->seller->name }}</b>,</p>

    <p>
        <b>{{ $book->user->name }}</b> no respondió a tu solicitud de extender la fecha de entrega de su servicio <b>{{ $book->service->name }}</b>, orden n.<b>{{ $book->id }}</b> dentro de las 24 horas y, de acuerdo con los <b><a href="{{ route('user.terms') }}">Términos y Condiciones</a></b> firmado, la nueva fecha de entrega se confirmó automáticamente para el día {{ $new_delivery_date }}.
    </p>
    <br/> <br/>

    @php
        \App::setLocale($oldLang);
    @endphp
@stop
