@extends('email.layout-en')

@section('email-content')
  <h2>Ciao {{$company_name}}</h2>
  <div>
    Grazie per esserti registrato su <b>{!! trans('main.sitename') !!}</b>
  </div>
  <div>
    Clicca <a href="{{ $link }}">qui</a> per attivare il tuo account
  </div>
  <hr/>

@stop
