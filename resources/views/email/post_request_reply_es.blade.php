@extends('email.layout-en')

@section('email-content')
  Hola <?php echo $user_name; ?>,<br><br>
  <?php echo $reply_name; ?> ha respondido a su solicitud POST <br>
  Los detalles de la solicitud son:<br>
  Título:  <?php echo $title; ?> <br>
  Descripción: <?php echo $description; ?><br>
  precio máximo para esta solicitud: <?php echo $budget; ?><br>
  La orden es válida hasta el día: <?php echo $expiry_date; ?><br>
  Mensaje: <?php echo $reply_message; ?><br><br>

@stop