@extends('email.layout-en')

@section('email-content')

    <p>Ciao <b>{{ $user->name." ".$user->surname }}</b>,</p>
    <p>utilizza il seguente codice
        @if($code->type != \App\Models\TFA::WITHDRAW)
          per confermare la prenotazione:
        @else
          per procedere e prelevare:
        @endif
      </p>

    <br>
    <center><p><b><h3>{{ $code->code }}</h3></b></p></center>
    <br>

    <p>
        @if($code->type != \App\Models\TFA::WITHDRAW)
          Dopo aver digitato il codice di verifica, la prenotazione verrà confermata e riceverai tutte
          le informazioni sul servizio prenotato.
        @else
          Dopo aver digitato il codice di verifica, potrai prelevare.
        @endif
      </p>
    <br>
    <p>
      <b>ATTENZIONE</b>
      <br>
      Il codice è disponibile solo per 15 min. Se non sei stato tu a fare questa richiesta, reimposta la
      password. In caso di domande contatta la nostra <a href="{{ route('user.contact-us') }}">Assistenza Clienti</a>.
    </p>

@stop