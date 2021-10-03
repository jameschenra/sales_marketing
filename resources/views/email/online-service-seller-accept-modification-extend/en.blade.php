@extends('email.layout-en')

@section('email-content')
    @php
        $lang = "en";
        $oldLang = \App::getLocale();
        \App::setLocale($lang);
    @endphp

    <p>Hello <b>{{ $book->user->name }}</b>,</p>

    <p>Your request to make changes for the service <b>{{ $book->service->name }}</b>, order no.<b>{{ $book->id }}</b>, has been accepted by <b>{{ $book->seller->name }}</b> and the delivery date has been moved to <b>{{ $book->delivery_date_formatted }}</b>.</p>
    <br>

    <p>Go to the order page to read the reasons that prompted {{ $book->seller->name }} to change the delivery date.</p>
    <br>

    <div class="text-align: center">
        <a href="{{ route('user.book.detail', ['id' => $book->id]) }}">
            <button class="view-btn btn-center">Go to order page</button>
        </a>
    </div> 
    <br><br>
    
    <p><b>Note:</b></p>
    
    <p>
        According to the signed <b><a href="{{ route('user.terms') }}">Terms & Conditions</a></b>, when changes are requested, the delivery date may vary. For any issues releated to this order and its delivery date, you can contact our Contact Support.
    </p>
    <br><br>

    @php
        \App::setLocale($oldLang);
    @endphp
@stop
