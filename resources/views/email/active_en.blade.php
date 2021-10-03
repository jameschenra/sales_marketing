@extends('email.layout-en')

@section('email-content')
  <p>Confirm registration on {{SITE_NAME}}</p><br>
  
  <p>Hello <b>{{$user->name}}</b>,</p><br>
  
  <p>thanks for signing up on <b>{{SITE_NAME}}</b>.</p><br>
  
  <p>Your account is created and need to be activated before to use it.</p><br>
  
  <p>To activate it click on the button:</p><br><br>
  
  <p><a href="{{ $active_link }}">
      <button class="view-btn hvr-pulse-grow load-categories" type="submit" value="button">Activate your Account
      </button>
    </a></p><br><br>
  
  <p>If you have problems with activation please copy and paste this link in your browser:</p><br>
  
  <p>{{ $active_link }}</p><br><br>
  
  <p>After activation, you can login using e-mail and password choices during
     registration.</p><br><br>
    
@stop
