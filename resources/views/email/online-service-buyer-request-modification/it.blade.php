@extends('email.layout-en')

@section('email-content')
    @php
        $lang = "it";
        $oldLang = \App::getLocale();
        \App::setLocale($lang);
    @endphp

    <p>Ciao <b>{{ $book->seller->name }}</b>,</p>

    <p><b>{{ $book->user->name }}</b> richiede delle modifiche sul suo servizio <b>{{ $book->service->name }}</b>, ordine n.<b>{{ $book->id }}</b>.</p>
    <br />

    <p>{!! $messageModel->message !!}</p>
    <br />

    <p>Vai alla pagina dell'ordine per vedere le modifiche richieste e rispondere a {{ $book->user->name }}.</p>
    <br />

    <div class="text-align: center">
        <a href="{{ route('user.orders.view', ['id' => $book->id]) }}">
            <button class="view-btn btn-center">Vai alla pagina dell'ordine</button>
        </a>
    </div> 
    <br />

    <p><b>Note:</b></p>

    <p>Secondo i <b><a href="{{ route('user.terms') }}">Termini e Condizioni</a></b> sottoscritti, puoi accettare le modifiche richieste, o accettare e contemporaneamente, se necessario, estendere la data di consegna.</p>
    <br /><br />

    @php
        \App::setLocale($oldLang);
    @endphp
@stop
