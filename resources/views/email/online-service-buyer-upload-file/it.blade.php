@extends('email.layout-en')

@section('email-content')
    @php
        $lang = "it";
        $oldLang = \App::getLocale();
        \App::setLocale($lang);
    @endphp

    <p>Ciao <b>{{ $book->seller->name }}</b>,</p>

    <p><b>{{ $book->user->name }}</b> ha inviato aggiornamenti sul suo servizio <b>{{ $book->service->name }}</b>, ordine n.<b>{{ $book->id }}</b>.</p>
    <br />

    <p><b>Messaggio: </b></p>

    <p>{{ $messageModel->message }}</p>
    <br />

    <p>Vai alla pagina dell'ordine per rispondere a {{ $book->user->name }}.</p>
    <br />

    <div class="text-align: center">
        <a href="{{ route('user.orders.view', ['id' => $book->id]) }}">
            <button class="view-btn btn-center">Vai alla pagina dell'ordine</button>
        </a>
    </div> 
    <br />

    @php
        \App::setLocale($oldLang);
    @endphp
@stop
