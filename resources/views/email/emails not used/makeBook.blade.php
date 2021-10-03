@extends('email.layout-en')

@section('email-content')
  <div>
    <h2>Congratulation!</h2>
  </div>
  <div>
    <p>You have booked on {!! trans('main.sitename') !!}</p>
    <table>
      <thead>
        <tr>
          <th>Service Name</th>
          <th>Address</th>
          @if(!empty($book_date))
            <th>Book Date</th>
          @endif
          @if(!empty($duration))
            <th>Duration</th>
          @endif
          @if(!empty($online_delivery_time))
            <th>Delivery Days</th>
          @endif
          <th>Total</th>
        </tr>
      </thead>
      <tbody>
        <tr>
          <td>{{$store_name}}</td>
          <td>{{$addr}}</td>
          @if(!empty($book_date))
            <td>{{$book_date}}</td>
          @endif
          @if(!empty($duration))
            <td>{{$duration}}</td>
          @endif
          @if(!empty($online_delivery_time))
            <td>{{ $online_delivery_time }}</td>
          @endif
          <td>{{ $price }}</td>
        </tr>
      </tbody>
    </table>
    <br/>
    <p>Message : {{ $msg }}</p>
  </div>
  <hr/>
  <div>
    <b>From</b> {!! trans('main.sitename') !!} <b>Team</b>
  </div>
@stop
