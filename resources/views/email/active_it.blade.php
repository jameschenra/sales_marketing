@extends('email.layout-en')

@section('email-content')
  <p>Conferma registrazione su {{SITE_NAME}}</p><br>
  
  <p>Ciao <b>{{$user->name}}</b>,</p><br>
  
  <p>grazie per esserti iscritto su <b>{{SITE_NAME}}</b>.</p><br>
  
  <p>Il tuo account è stato creato e deve essere attivato prima di poter accedere.</p><br>
  
  <p>Per attivarlo clicca sul pulsante:</p><br><br>
  
  <p><a href="{{ $active_link }}">
      <button class="view-btn hvr-pulse-grow load-categories" type="submit" value="button">Attiva il tuo Account
      </button>
    </a></p><br><br>
  
  <p>Se hai problemi con l’attivazione, copia e incolla il seguente link nella barra di navigazione:</p><br>
  
  <p>{{ $active_link }}</p><br><br>
  
  <p>Dopo l'attivazione puoi accedere utilizzando l'indirizzo e-mail e la password che hai scelto durante la 
     registrazione.</p><br><br>
    
@stop
