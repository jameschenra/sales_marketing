@extends('email.layout-en')

@section('email-content')
  <div>
    <h2>Congratulation!</h2>
  </div>
  <div>
    <p>You get the offers from {!! trans('main.sitename') !!}</p>
    <p>Offer Name : {{ $offer_name }}</p>
    <p>Offer Description : {{ $offer_description }}</p>
    <p>Offer Code : {{ $offer_code }}</p>
    <p>Company Name : <a href="{{ $company_link }}" target="_blank">{{ $company_name }} </a></p>
  </div>
  <hr/>
  <div>
    <b>From {!! trans('main.sitename') !!} Team</b>
  </div>
@stop
