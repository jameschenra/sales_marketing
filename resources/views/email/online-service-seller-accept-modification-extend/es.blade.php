@extends('email.layout-en')

@section('email-content')
    @php
        $lang = "en";
        $oldLang = \App::getLocale();
        \App::setLocale($lang);
    @endphp

    <p>Hola <b>{{ $book->user->name }}</b>,</p>

    <p>Tu solicitud para realizar cambios por el servicio <b>{{ $book->service->name }}</b>, orden n.<b>{{ $book->id }}</b>, ha sido aceptada por <b>{{ $book->seller->name }}</b> y la fecha de entrega se ha trasladado a <b>{{ $book->delivery_date_formatted }}</b>.</p>
    <br>

    <p>Ve a la página del pedido para leer las razones que llevaron a {{ $book->seller->name }} a cambiar la fecha de entrega.</p>
    <br>

    <div class="text-align: center">
        <a href="{{ route('user.book.detail', ['id' => $book->id]) }}">
            <button class="view-btn btn-center">Ve a la página del pedido</button>
        </a>
    </div> 
    <br><br>
    
    <p><b>Note:</b></p>
    
    <p>
        De acuerdo con los <b><a href="{{ route('user.terms') }}">Términos y Condiciones</a></b> firmados, cuando se solicitan cambios, la fecha de entrega puede variar. Para problemas relacionados con este pedido y su fecha de entrega, puedes contactar con nuestro servicio de Atención al Cliente.
    </p>
    <br><br>

    @php
        \App::setLocale($oldLang);
    @endphp
@stop
