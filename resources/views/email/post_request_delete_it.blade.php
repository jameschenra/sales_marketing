@extends('email.layout-en')

@section('email-content')
  Ciao <?php echo $company_name; ?>,<br><br>
  <?php echo $request_creator_name; ?> Avete cancellato una richiesta <br>
  I dettagli della richiesta sono:<br>
  Titolo:  <?php echo $title; ?> <br>
  Descrizione: <?php echo $description; ?><br>
  Prezzo massimo per questa richiesta: <?php echo $budget; ?><br>
  L'ordine è valido fino al giorno: <?php echo $expiry_date; ?><br>

@stop