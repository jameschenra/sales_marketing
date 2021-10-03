@extends('email.layout-en')

@section('email-content')
  <div>
    {{ $company_name }} has sent message to you
  </div>
  <br/>
  <hr/>
  {{ $description }}
  <hr/>
  <br/>
  
@stop
