@extends('email.layout-en')

@section('email-content')
  
  <p>Hi, my name is <b>{{ $name }}</b>,</p> 
  
  <p>I have a request for assistance regarding <b>{{ $option }}</b></p>
  
  <p>Here is my description:</p><br>  
  
  <p>{{ $message1 }}</p><br>
  
  <p>My contact details are:</p>
  
  <p>Email: {{ $email }}</p>
  
  <p>Telephone: {{ $telephone or '' }}</p><br>
  
  <p>I look forward to receiving your feedback.</p>
  
  <p>Thanks in advance</p>
  
  <p>{{ $name }}</p>
    
@stop
