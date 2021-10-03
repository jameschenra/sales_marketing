@extends('email.layout-en')

@section('email-content')
    @php
        $lang = "en";
        $oldLang = \App::getLocale();
        \App::setLocale($lang);
    @endphp

    <p>Hello <b>{{ $book->user->name }}</b>,</p>

    <p>your request to make changes for the service <b>{{ $book->service->name }}</b>, order no.<b>{{ $book->id }}</b>, has been accepted by <b>{{ $book->seller->name }}</b></p>
    <p>You'll receive your service within the expected delivery date.</p>
    <br><br>
    @php
        \App::setLocale($oldLang);
    @endphp
@stop
