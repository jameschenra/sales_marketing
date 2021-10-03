@extends('email.layout-en')

@section('email-content')
  <h2>Hi {{$company_name}}</h2>
  <div>
    Welcome to Registration of <b>{!! trans('main.sitename') !!}</b>
  </div>
  <div>
    Click <a href="{{ $link }}">here</a> to activate your account
  </div>
  <hr/>
  
@stop
