@extends('email.layout-en')

@section('email-content')
  <div>
    {{ $company_name }} te ha enviado un mensaje
  </div>
  <br/>
  <hr/>
  {{ $description }}
  <hr/>
  <br/>
  
@stop
