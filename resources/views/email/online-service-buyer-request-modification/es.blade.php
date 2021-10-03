@extends('email.layout-en')

@section('email-content')
    @php
        $lang = "es";
        $oldLang = \App::getLocale();
        \App::setLocale($lang);
    @endphp

    <p>Hola <b>{{ $book->seller->name }}</b>,</p>

    <p><b>{{ $book->user->name }}</b> requiere cambios en su servicio <b>{{ $book->service->name }}</b>, orden n.<b>{{ $book->id }}</b>.</p>
    <br />

    <p>{!! $messageModel->message !!}</p>
    <br />

    <p>Ve a la página del pedido para ver los cambios solicitados y responder a {{ $book->user->name }}.</p>
    <br />

    <div class="text-align: center">
        <a href="{{ route('user.orders.view', ['id' => $book->id]) }}">
            <button class="view-btn btn-center">Ve a la página del pedido</button>
        </a>
    </div> 
    <br />

    <p><b>Note:</b></p>

    <p>De acuerdo con los <b><a href="{{ route('user.terms') }}">Términos y Condiciones</a></b> firmados, puedes aceptar los cambios solicitados, o aceptar y al mismo tiempo, si es necesario, extender la fecha de entrega.</p>
    <br /><br />

    @php
        \App::setLocale($oldLang);
    @endphp
@stop
