@extends('email.layout-en')

@section('email-content')
    @php
        $lang = "es";
        $oldLang = \App::getLocale();
        \App::setLocale($lang);
    @endphp

    <p>Hola <b>{{ $book->seller->name }}</b>,</p>

    <p><b>{{ $book->user->name }}</b> no aceptó su solicitud de extender la fecha de entrega de su servicio <b>{{ $book->service->name }}</b>, orden n.<b>{{ $book->id }}</b>.</p>
    <br />

    <p><b>Razón por la que no fue aceptada: </b> {{ $cancel_reason }}</p>
    <br /><br />

    <p>Ve a la página del pedido y vuelva a intentarlo ingresando una fecha diferente o envíe un mensaje.</p>
    <br />

    <div class="text-align: center">
        <a href="{{ route('user.orders.view', ['id' => $book->id]) }}">
            <button class="view-btn btn-center">Ve a la página del pedido</button>
        </a>
    </div>
    <br />

    @php
        \App::setLocale($oldLang);
    @endphp
@stop
