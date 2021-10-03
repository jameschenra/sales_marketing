@extends('email.layout-en')

@section('email-content')
    @php
        $lang = "it";
        $oldLang = \App::getLocale();
        \App::setLocale($lang);
    @endphp

    <p>Nuova recensione inserita da <b>{{ $review->user->name }}</b> per il servizio <b>{{ $review->book->service->name }}</b></p>
    <p><b>Voto dato:</b> {{ $review->rate }}</p>
    <p><b>Recensione:</b> {{ $review->review }}</p>
@stop