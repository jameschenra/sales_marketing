@extends('email.layout-en')

@section('email-content')
  Hello <?php echo $company_name; ?>,<br><br>
  It was published a new request for your profession <?php echo $company_name; ?>.<br>
  The details of the request are:<br>
  title:  <?php echo $title; ?> <br>
  Description: <?php echo $description; ?><br>
  Maximum price for this request: <?php echo $budget; ?><br>
  The order is valid until the day: <?php echo $expiry_date; ?><br>
  Name: <?php echo $request_creator_name; ?><br>
  Reply to username: <a href="<?php echo $base_url; ?>/request/reply/<?php echo $insert_id; ?>" title="Reply to user">Reply</a>
  <br><br>

@stop