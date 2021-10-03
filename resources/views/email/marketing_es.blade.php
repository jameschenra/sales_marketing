@extends('email.layout-en')

@section('email-content')
  <p>
    Nombre del Profesional : {{ $company_name }}
  </p>
  <div>
    {{ $msg }}
  </div>
  <hr/>
  
@stop
