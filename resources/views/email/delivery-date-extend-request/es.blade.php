@extends('email.layout-en')

@section('email-content')
    @php
        $lang = "es";
        $oldLang = \App::getLocale();
        \App::setLocale($lang);
    @endphp

    <p>Ciao <b>{{ $book->user->name }}</b>,</p>

    <p><b>{{ $book->seller->name }}</b> quiere extender la fecha de entrega de tu servicio <b>{{ $book->service->name }}</b>, orden n.<b>{{ $book->id }}</b>.</p>
    <br />

    <p>{!! $request_message !!}</p>
    <br />

    <p>Ve a la página del pedido para confirmar o no la nueva fecha.</p>
    <br /><br />
    
    <div class="text-align: center">
        <a href="{{ route('user.book.detail', ['id' => $book->id]) }}">
            <button class="view-btn btn-center">Ve a la página del pedido</button>
        </a>
    </div>
    <br />

    <p><b>Nota:</b> </p>

    <p>
        Si no respondes dentro de las 24 horas, de acuerdo con los <b><a href="{{ route('user.terms') }}">Términos y Condiciones</a></b> firmado la nueva fecha se confirmará automáticamente.
    </p>
    <br /><br />

    @php
        \App::setLocale($oldLang);
    @endphp
@stop
