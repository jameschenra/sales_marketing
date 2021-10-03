@extends('email.layout-en')

@section('email-content')
    @php
        $lang = "en";
        $oldLang = \App::getLocale();
        \App::setLocale($lang);
    @endphp

    <p>Hello <b>{{ $book->user->name }}</b>,</p>

    <p><b>{{ $book->seller->name }}</b> wants to extend the delivery date for your service <b>{{ $book->service->name }}</b>, order no.<b>{{ $book->id }}</b>.</p>
    <br />

    <p>{!! $request_message !!}</p>
    <br />

    <p>Go to the order page to confirm or not the new date.</p>
    <br /><br />

    <div class="text-align: center">
        <a href="{{ route('user.book.detail', ['id' => $book->id]) }}">
            <button class="view-btn btn-center">Go to order page</button>
        </a>
    </div>
    <br />

    <p><b>Note:</b></p>

    <p>
        If you don't reply within 24 hours, according to <b><a href="{{ route('user.terms') }}">Terms & Conditions</a></b> signed the new date will be automatically confirmed.
    </p>
    <br /><br />

    @php
        \App::setLocale($oldLang);
    @endphp
@stop
