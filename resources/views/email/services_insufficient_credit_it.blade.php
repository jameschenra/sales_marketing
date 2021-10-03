@extends('email.layout-en')

@section('email-content')
  
  <p>Ciao <b>{{ $name }}</b>,</p>

  <p>I tuoi servizi gratuiti non saranno visualizzati e la possibilità di pagare in sede non sarà disponibile 
     per credito insufficiente.</p><br>

  <p>Per consentire ai tuoi clienti di visualizzare i tuoi servizi gratuiti e di prenotare e pagare
     direttamente nelle tue sedi devi avere un saldo minimo disponibile di
     €{{ MINIMUM_BALANCE }},00.
     Nessuna somma verrà prelevata fino a quando i tuoi clienti non effettueranno ordini o prenotazioni.
     Per ogni servizio ordinato o prenotato verrà addebitata una fee di €{{FEE}},00. Puoi
     aggiungere credito adesso dalla pagina del <b><a href="{{route('user.balance.show')}}">Saldo</a></b>.<p><br><br>

  <p>Puoi disattivare il pagamento in sede e la pubblicazione di servizi gratuiti dalla pagina delle <b>
  <a href="{{ URL::route('company.profile', 'settings') }}">Impostazioni</a></b>.</p>

@stop