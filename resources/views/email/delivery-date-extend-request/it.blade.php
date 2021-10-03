@extends('email.layout-en')

@section('email-content')
    @php
        $lang = "it";
        $oldLang = \App::getLocale();
        \App::setLocale($lang);
    @endphp

    <p>Ciao <b>{{ $book->user->name }}</b>,</p>

    <p><b>{{ $book->seller->name }}</b> vuole estendere la data di consegna del tuo servizio <b>{{ $book->service->name }}</b>, ordine n.<b>{{ $book->id }}</b>.</p>
    <br />

    <p>{!! $request_message !!}</p>
    <br />

    <p>Vai alla pagina dell'ordine per confermare o meno la nuova data.</p>
    <br /><br />
        
    <div class="text-align: center">
        <a href="{{ route('user.book.detail', ['id' => $book->id]) }}">
            <button class="view-btn btn-center">Vai alla pagina dell'ordine</button>
        </a>
    </div>
    <br />

    <p><b>Nota:</b> </p>

    <p>
        Se non rispondi entro 24 ore, secondo i <b><a href="{{ route('user.terms') }}">Termini e Condizioni</a></b> sottoscritti la nuova data verrà automaticamente confermata.
    </p>
    <br /><br />

    @php
        \App::setLocale($oldLang);
    @endphp
@stop
