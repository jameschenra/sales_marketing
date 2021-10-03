@extends('email.layout-en')

@section('email-content')
  <p>Ciao l'utente {{ $name }}</p>
  <p>necessita di suporto su <a href="i.weredy.com">my.weredy.com</a></p></br>
  <p>I suoi dati sono:</p>
  <p>Nome: {{ $name }}</p>
  <p>E-mail: {{ $email }}</p>
  <p>Messaggio: {{ $message1 }}</p>

@stop