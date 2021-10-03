@extends('email.layout-en')

@section('email-content')
    <p>Hi <b>{{ $name }}</b>,
   
    <p>thanks for contacting our <b>Customer Support</b>.</p>
   
    <p>We have in charge your request and we’ll contact you soon.</p>
    
@stop
