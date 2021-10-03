@extends('email.layout-en')

@section('email-content')
  <div>
    {{ $company_name }} ti ha inviato un messaggio
  </div>
  <br/>
  <hr/>
  {{ $description }}
  <hr/>
  <br/>
  
@stop