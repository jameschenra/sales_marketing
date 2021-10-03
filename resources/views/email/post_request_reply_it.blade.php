@extends('email.layout-en')

@section('email-content')
  Ciao <?php echo $user_name; ?>,<br><br>
  <?php echo $reply_name; ?> ha risposto alla richiesta posta <br>
  I dettagli della richiesta sono:<br>
  Titolo:  <?php echo $title; ?> <br>
  Descrizione: <?php echo $description; ?><br>
  Prezzo massimo per questa richiesta: <?php echo $budget; ?><br>
  L'ordine è valido fino al giorno: <?php echo $expiry_date; ?><br>
  Messaggio: <?php echo $reply_message; ?><br><br>
  
  @stop