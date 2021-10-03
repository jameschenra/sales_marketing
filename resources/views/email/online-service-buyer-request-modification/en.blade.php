@extends('email.layout-en')

@section('email-content')
    @php
        $lang = "en";
        $oldLang = \App::getLocale();
        \App::setLocale($lang);
    @endphp

    <p>Hello <b>{{ $book->seller->name }}</b>,</p>

    <p><b>{{ $book->user->name }}</b> requires changes on its service <b>{{ $book->service->name }}</b>, order no.<b>{{ $book->id }}</b>.</p>
    <br />

    <p>{!! $messageModel->message !!}</p>
    <br />

    <p>Go to the order page to see the requested changes and answer to {{ $book->user->name }}.</p>
    <br />

    <div class="text-align: center">
        <a href="{{ route('user.orders.view', ['id' => $book->id]) }}">
            <button class="view-btn btn-center">Go to order page</button>
        </a>
    </div> 
    <br />

    <p><b>Note:</b></p>

    <p>According to the signed <b><a href="{{ route('user.terms') }}">Terms & Conditions</a></b>, you can accept the requested changes, or accept and at the same time, if necessary, extend the delivery date.</p>
    <br /><br />

    @php
        \App::setLocale($oldLang);
    @endphp
@stop
