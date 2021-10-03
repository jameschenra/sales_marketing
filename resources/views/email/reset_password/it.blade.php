@extends('email.layout-en')

@section('email-content')

  <p>Abbiamo ricevuto una richiesta per impostare una nuova password. Per poter resettare la tua password clicca sul pulsante:</p><br><br>

  <p><a href="{{ $reset_link }}">
      <button class="view-btn hvr-pulse-grow load-categories" type="submit" value="button">Resetta la tua password
      </button>
    </a></p><br><br>

  <p>Se non hai richiesto il cambio della password ignora questa e-mail.</p>

@stop