@extends('email.layout-en')

@section('email-content')
    <p>Hola <b>{{ $name }}</b>,</p>
 
    <p>gracias por ponerse en contacto con nuestra <b>Atención al Cliente</b>.</p>
 
    <p>Ya tenemos los datos de tu solicitud y pronto nos pondremos en contacto.</p>
    
@stop
