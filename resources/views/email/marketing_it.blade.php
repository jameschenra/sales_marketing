@extends('email.layout-en')

@section('email-content')
  <p>
    Nome del Professionista : {{ $company_name }}
  </p>
  <div>
    {{ $msg }}
  </div>
  <hr/>
  
@stop
