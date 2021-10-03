@extends('email.layout-en')

@section('email-content')
  Hello <?php echo $user_name; ?>,<br><br>
  <?php echo $reply_name; ?> has replied on your post request <br>
  The details of the request are:<br>
  title:  <?php echo $title; ?> <br>
  Description: <?php echo $description; ?><br>
  Maximum price for this request: <?php echo $budget; ?><br>
  The order is valid until the day: <?php echo $expiry_date; ?><br>
  Message: <?php echo $reply_message; ?><br><br>

@stop