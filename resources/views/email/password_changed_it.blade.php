@extends('email.layout-en')

@section('email-content')
  <p>Ciao <b>{{ $username }}</b>,</p><br>
  
  <p>La password per il tuo account è stata cambiata con successo.</p><br>
  
  <p>Se non hai richiesto questa operazione contatta immediatamente la nostra <a href="{{ route('user.contact-us') }}">Assistenza Clienti</a>.</p><br><br>

@stop