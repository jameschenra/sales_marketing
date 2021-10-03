@extends('email.layout-en')

@section('email-content')
  <p>
    Company Name : {{ $company_name }}
  </p>
  <div>
    {{ $msg }}
  </div>
  <hr/>
  
@stop
