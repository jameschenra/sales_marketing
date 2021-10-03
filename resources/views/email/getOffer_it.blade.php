@extends('email.layout-en')

@section('email-content')
  <div>
    <h2>Congratulazioni!</h2>
  </div>
  <div>
    <p>Hai ricevuto un offerta da {!! trans('main.sitename') !!}</p>
    <p>Nome dell'Offerta : {{ $offer_name }}</p>
    <p>Descrizione : {{ $offer_description }}</p>
    <p>Codice : {{ $offer_code }}</p>
    <p>Nome del Professionista : <a href="{{ $company_link }}" target="_blank">{{ $company_name }} </a></p>
  </div>
  <hr/>

@stop
