@extends('email.layout-en')

@section('email-content')
  <p>Hola <b>{{ $username }}</b>,</p><br>
  
  <p>La contraseña para tu cuenta se ha cambiado con exito.</p><br>
  
  <p>Si no ha realizado el cambio, pongase en contacto con nuestro servicio de <a href="{{ route('user.contact-us') }}">Atención al Cliente</a> inmediatamente.</p><br><br>

@stop