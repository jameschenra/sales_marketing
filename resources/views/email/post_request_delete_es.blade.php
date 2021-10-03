@extends('email.layout-en')

@section('email-content')
  Hola <?php echo $company_name; ?>,<br><br>
  <?php echo $request_creator_name; ?> Se ha eliminado a petición <br>
  Los detalles de la solicitud son:<br>
  Título:  <?php echo $title; ?> <br>
  Descripción: <?php echo $description; ?><br>
  precio máximo para esta solicitud: <?php echo $budget; ?><br>
  La orden es válida hasta el día: <?php echo $expiry_date; ?><br>

@stop