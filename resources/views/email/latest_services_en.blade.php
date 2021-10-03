@extends('email.layout-en')

@section('email-content')
  <div>
    <h2>Latest Services</h2>
  </div>
  <div>
    @foreach($services as $service)
      <p>{{ $service->name }}</p>
    @endforeach
  </div>
  <a href="{{ URL::to('/') }}"> Read More </a>
  <hr/>
  <div>

    <b>From {{ $site_name }} Team</b>
  </div>
@stop
