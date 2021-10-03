@extends('email.layout-en')

@section('email-content')
    @php
        $lang = "es";
        $oldLang = \App::getLocale();
        \App::setLocale($lang);
    @endphp

    <p>Nueva opinión publicada por <b>{{ $review->user->name }}</b> por el servicio <b>{{ $review->book->service->name }}</b></p>
    <p><b>Voto dato:</b> {{ $review->rate }}</p>
    <p><b>Opinión:</b> {{ $review->review }}</p>
@stop