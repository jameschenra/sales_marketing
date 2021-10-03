@extends('email.layout-en')

@section('email-content')
  <p>Hello <b>{{ $username }}</b>,</p><br>
  
  <p>The password for your account has successfully been changed.</p><br>
  
  <p>If you did not initiate this change, please contact our <a href="{{ route('user.contact-us') }}">Contact Support</a> immediately.</p><br><br>

@stop