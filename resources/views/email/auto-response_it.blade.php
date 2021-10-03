@extends('email.layout-en')

@section('email-content')
    <p>Ciao <b>{{ $name }}</b>,</p>
  
    <p>grazie per aver contattato la nostra <b>Assistenza Clienti</b>.</p>
  
    <p>Abbiamo preso in carico la tua richiesta e ti contatteremo al più presto.</p>
    
@stop
