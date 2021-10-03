@extends('email.layout-en')

@section('email-content')
  <h2>Hola {{$company_name}}</h2>
  <div>
    Gracias por registrarse en {!! trans('main.sitename') !!}
  </div>
  <div>
    Haga clic <a href="{{ $link }}">aqui</a> para activar tu cuenta.
  </div>
  <hr/>
  
@stop
