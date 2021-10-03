@extends('email.layout-en')

@section('email-content')

  <p>Hello <b>{{ $name }}</b>,</p>
    
  <p>Your free services will not be displayed and payment to your offices is not available due to insufficient 
     balance.</p><br>

  <p>In order to allow your customers to view your free services and to book and pay to your
     offices direct, you need to add credit into your balance.
     You need to have a minimum balance of €{{ MINIMUM_BALANCE }},00. No money will be withdrawn
     until customers place orders or bookings. Then a fee of €{{FEE}},00 will be taken for each ordered 
     or booked service. You can add credit now from your <b><a href="{{route('user.balance.show')}}">Balance</a></b> page.</p><br><br>

  <p>The payment in the office and the publication of free services can be deactivated from the <b><a href="{{ URL::route('company.profile', 'settings') }}">Settings</a></b> page.</p>

@stop