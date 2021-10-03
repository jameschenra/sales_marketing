@extends('email.layout-en')

@section('email-content')
    @php
        $lang = "en";
        $oldLang = \App::getLocale();
        \App::setLocale($lang);
    @endphp

    <p>Review added from <b>{{ $review->user->name }}</b> for book <b>{{ $review->book->service->name }}</b></p>
    <p><b>Review rate:</b> {{ $review->rate }}</p>
    <p><b>Review content:</b> {{ $review->review }}</p>
@stop