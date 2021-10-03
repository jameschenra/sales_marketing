@extends('email.layout-en')

@section('email-content')

    <p>Hello <b>{{ $user->name." ".$user->surname }}</b>,</p>
    <p>please use the following code to
        @if($code->type != \App\Models\TFA::WITHDRAW)
          confirm your booking:
        @else
          proceed and withdraw:
        @endif
      </p>

    <br>
    <center><p><b><h3>{{ $code->code }}</h3></b></p></center>
    <br>

    <p>
        @if($code->type != \App\Models\TFA::WITHDRAW)
          Once you will type the verification code your booking will be confirmed and you'll
          receive all information about your service booked.
        @else
          Once you will type the verification code you will be able to withdraw.
        @endif
      </p>
    <br>
    <p>
      <b>ATTENTION</b>
      <br>
      The code is available only for 15 min. If this wasn’t you to make this request, please reset your
      password. If you have any questions please contact our <a href="{{ route('user.contact-us') }}">Contact Support</a>.
    </p>
@stop