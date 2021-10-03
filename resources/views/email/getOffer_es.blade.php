@extends('email.layout-en')

@section('email-content')
  <div>
    <h2>¡Felicidades</h2>
  </div>
  <div>
    <p>Usted tiene una oferta de {!! trans('main.sitename') !!}</p>
    <p>Nombre de la Oferta : {{ $offer_name }}</p>
    <p>Descripción de la oferta : {{ $offer_description }}</p>
    <p>Codigi : {{ $offer_code }}</p>
    <p>Nombre del Profesional : <a href="{{ $company_link }}" target="_blank">{{ $company_name }} </a></p>
  </div>
  <hr/>
  
@stop
