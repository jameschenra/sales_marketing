@extends('email.layout-en')

@section('email-content')
  <div>
    <h4>Hi {{$user_name}}</h4>
  </div>
  <div>
    <p>Your booking was {{$status}} by {{$store_name}}</p>
    <p>Book Date : {{ $book_date }}</p>
    <p>Duration : {{ $duration }}</p>
  </div>
  <hr/>
  <div>
    <b>From {!! trans('main.sitename') !!} Team</b>
  </div>
@stop