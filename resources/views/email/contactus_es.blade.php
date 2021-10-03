@extends('email.layout-en')

@section('email-content')
  
  <p>Hola, mi nombre es <b>{{ $name }}</b>,</p> 
  
  <p>tengo una solicitud de asistencia por <b>{{ $option }}</b></p>
  
  <p>Enseguida mi descripción:</p><br>  
  
  <p>{{ $message1 }}</p><br>
  
  <p>Mis detalles de contacto son:</p>
  
  <p>Correo electronico: {{ $email }}</p>
  
  <p>Telefono: {{ $telephone or '' }}</p><br>
  
  <p>Resto in attesa di un vestro riscontro.</p>
  
  <p>Muchas gracias</p>
  
  <p>{{ $name }}</p>
       
@stop
