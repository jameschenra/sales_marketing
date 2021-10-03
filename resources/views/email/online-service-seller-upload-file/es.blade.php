@extends('email.layout-en')

@section('email-content')
    @php
        $lang = "es";
        $oldLang = \App::getLocale();
        \App::setLocale($lang);
    @endphp

    <p>Hola <b>{{ $book->user->name }}</b>,</p>

    <p><b>{{ $book->seller->name }}</b> envió actualizaciones sobre tu servicio <b>{{ $book->service->name }}</b>, orden n.<b>{{ $book->id }}</b>.</p>
    <br />

    <p><b>Mensaje: </b></p>

    <p>{{ $messageModel->message }}</p>
    <br />

    <p>Ve a la página del pedido para responder {{ $book->seller->name }}.</p>
    <br />

    <div class="text-align: center">
        <a href="{{ route('user.book.detail', ['id' => $book->id]) }}">
            <button class="view-btn btn-center">Ve a la página del pedido</button>
        </a>
    </div> 
    <br />

    @php
        \App::setLocale($oldLang);
    @endphp
@stop
