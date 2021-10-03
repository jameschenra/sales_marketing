@extends('email.layout-en')

@section('email-content')
    @php
        $lang = "en";
        $oldLang = \App::getLocale();
        \App::setLocale($lang);
    @endphp

    <p>Hello <b>{{ $book->seller->name }}</b>,</p>

    <p><b>{{ $book->user->name }}</b> sent updates about his service <b>{{ $book->service->name }}</b>, order no.<b>{{ $book->id }}</b>.</p>
    <br />

    <p><b>Message: </b></p>

    <p>{{ $messageModel->message }}</p>
    <br />

    <p>Go to the order page to reply to {{ $book->user->name }}.</p>
    <br />

    <div class="text-align: center">
        <a href="{{ route('user.orders.view', ['id' => $book->id]) }}">
            <button class="view-btn btn-center">Go to order page</button>
        </a>
    </div> 
    <br />

    @php
        \App::setLocale($oldLang);
    @endphp
@stop
