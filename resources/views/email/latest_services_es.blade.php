@extends('email.layout-en')

@section('email-content')
  <div>
    <h2>Latest Services</h2>
  </div>
  <div>
    @if($service->name_es != '')
      <p>{{ $service->name_es }}</p>
    @else
      <p>{{ $service->name_en }}</p>
    @endif
  </div>
  <a href="{{ URL::to('/') }}"> Read More </a>
  <hr/>
  <div>

    <b>From {{ $site_name }} Team</b>
  </div>
@stop
