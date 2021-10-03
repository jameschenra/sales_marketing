@extends('email.layout-en')

@section('email-content')
  Ciao <?php echo $company_name; ?>,<br><br>
  E 'stato pubblicato una nuova richiesta per la tua professione <?php echo $company_name; ?>.<br>
  I dettagli della richiesta sono:<br>
  Titolo:  <?php echo $title; ?> <br>
  Descrizione: <?php echo $description; ?><br>
  Prezzo massimo per questa richiesta: <?php echo $budget; ?><br>
  L'ordine è valido fino al giorno: <?php echo $expiry_date; ?><br>
  Nome: <?php echo $request_creator_name; ?><br>
  Rispondi a nome utente: <a href="<?php echo $base_url; ?>/request/reply/<?php echo $insert_id; ?>"
                             title="Rispondi a utente">rispondere</a><br><br>

@stop
