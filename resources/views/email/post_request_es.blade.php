@extends('email.layout-en')

@section('email-content')
  Hola <?php echo $company_name; ?>,<br><br>
  Se publicó una nueva solicitud para su profesión <?php echo $company_name; ?>.<br>
  Los detalles de la solicitud son:<br>
  Título:  <?php echo $title; ?> <br>
  Descripción: <?php echo $description; ?><br>
  precio máximo para esta solicitud: <?php echo $budget; ?><br>
  La orden es válida hasta el día: <?php echo $expiry_date; ?><br>
  Nombre: <?php echo $request_creator_name; ?><br>
  Responder a nombre de usuario: <a href="<?php echo $base_url; ?>/request/reply/<?php echo $insert_id; ?>"
                                    title="Responder al usuario">Respuesta</a><br><br>

@stop