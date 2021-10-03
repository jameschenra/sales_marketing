@extends('email.layout-en')

@section('email-content')
  Hello <?php echo $company_name; ?>,<br><br>
  <?php echo $request_creator_name; ?> has deleted a request <br>
  The details of the request are:<br>
  title:  <?php echo $title; ?> <br>
  Description: <?php echo $description; ?><br>
  Maximum price for this request: <?php echo $budget; ?><br>
  The order is valid until the day: <?php echo $expiry_date; ?><br>

@stop