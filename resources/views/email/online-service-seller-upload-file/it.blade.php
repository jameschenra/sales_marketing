@extends('email.layout-en')

@section('email-content')
    @php
        $lang = "it";
        $oldLang = \App::getLocale();
        \App::setLocale($lang);
    @endphp

    <p>Ciao <b>{{ $book->user->name }}</b>,</p>

    <p><b>{{ $book->seller->name }}</b> ha inviato aggiornamenti sul tuo servizio <b>{{ $book->service->name }}</b>, ordine n.<b>{{ $book->id }}</b>.</p>
    <br />

    <p><b>Messaggio: </b></p>

    <p>{{ $messageModel->message }}</p>
    <br />

    <p>Vai alla pagina dell'ordine per rispondere a {{ $book->seller->name }}.</p>
    <br />

    <div class="text-align: center">
        <a href="{{ route('user.book.detail', ['id' => $book->id]) }}">
            <button class="view-btn btn-center">Vai alla pagina dell'ordine</button>
        </a>
    </div> 
    <br />

    @php
        \App::setLocale($oldLang);
    @endphp
@stop
