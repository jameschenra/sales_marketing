@extends('email.layout-en')

@section('email-content')
  <div>
    <h2>Congratulation!</h2>
  </div>
  <div>
    <p>A customer have booked on your {{ $store_name }} service</p>
    <table>
      <thead>
        <tr>
          <th>Service Name</th>
          <th>Office</th>
          <th>Address</th>
          <th>User Name</th>
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
          <td>{{$location}}</td>
          <td>{{$addr}}</td>
          <td>{{$user_name}}</td>
          @if(!empty($book_date))
            <td>{{$book_date}}</td>
          @endif
          @if(!empty($duration))
            <td>{{$duration}}</td>
          @endif
          @if(!empty($online_delivery_time))
            <td>$online_delivery_time</td>
          @endif
          <td>$price</td>
        </tr>
      </tbody>
    </table>

    <p>Message : {{ $msg }}</p>
  </div>
  <hr/>
  <div>
    <b>From {!! trans('main.sitename') !!} Team</b>
  </div>
@stop
