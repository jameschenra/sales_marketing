@extends('email.layout-en')

@section('email-content')
    @php
        $lang = "en";
        $oldLang = \App::getLocale();
        \App::setLocale($lang);
    @endphp

    <p>Hello <b>{{ $book->seller->name }}</b>,</p>
   
    <p>
        <b>{{ $book->user->name }}</b> did not respond to your request to extend the delivery date of his service <b>{{ $book->service->name }}</b>, order no.<b>{{ $book->id }}</b> within 24 hours and, according to <b><a href="{{ route('user.terms') }}">Terms & Conditions</a></b> signed, the new delivery date was automatically confirmed for the day {{ $new_delivery_date }}.
    </p>
    <br/> <br/>

    @php
        \App::setLocale($oldLang);
    @endphp
@stop
