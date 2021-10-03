@extends('email.layout-en')

@section('email-content')
  
  <p>Salve sono <b>{{ $name }}</b>,</p> 
  
  <p>ho una richiesta di assistenza riguardante <b>{{ $option }}</b></p>
  
  <p>Di seguito la mia descrizione:</p><br> 
  
  <p>{{ $message1 }}</p><br>
  
  <p>I miei dati di contatto sono:</p>
  
  <p>Email: {{ $email }}</p>
  
  <p>Telefono: {{ $telephone or '' }}</p><br>
  
  <p>Resto in attesa di un vostro riscontro.</p>
  
  <p>Grazie mille</p>
  
  <p>{{ $name }}</p>
    
@stop
