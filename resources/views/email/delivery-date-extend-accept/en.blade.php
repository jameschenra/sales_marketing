@extends('email.layout-en')

@section('email-content')
    @php
        $lang = "en";
        $oldLang = \App::getLocale();
        \App::setLocale($lang);
    @endphp

    <p>Hello <b>{{ $book->seller->name }}</b>,</p>

    <p><b>{{ $book->user->name }}</b> accepted your request to extend the delivery date of his service <b>{{ $book->service->name }}</b>, order no.<b>{{ $book->id }}</b>.</p>
    <br/>

    <p>The new delivery date it will be {{ $new_delivery_date }}.</p>
    <br/>

    @php
        \App::setLocale($oldLang);
    @endphp
@stop
