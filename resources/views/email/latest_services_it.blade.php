@extends('email.layout-en')

@section('email-content')
  <div>
    <h2>Latest Services</h2>
  </div>
  <div>
    @foreach($services as $service)
      @if($service->name_it != '')
        <p>{{ $service->name_it }}</p>
      @else
        <p>{{ $service->name_en }}</p>
      @endif
    @endforeach
  </div>
  <a href="{{ URL::to('/') }}"> Read More </a>
  <hr/>
  <div>

    <b>From {{ $site_name }} Team</b>
  </div>
@stop
