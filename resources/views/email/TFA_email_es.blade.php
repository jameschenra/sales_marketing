@extends('email.layout-en')

@section('email-content')

    <p>Hola <b>{{ $user->name." ".$user->surname }}</b>,</p>
    <p>utilice el siguiente código
        @if($code->type != \App\Models\TFA::WITHDRAW)
          para confirmar tu reserva:
        @else
          para continuar y retirar:
        @endif
      </p>

    <br>
    <center><p><b><h3>{{ $code->code }}</h3></b></p></center>
    <br>

    <p>
        @if($code->type != \App\Models\TFA::WITHDRAW)
          Una vez que ingrese el código de verificación, se confirmará tu reserva y recibirás toda
          la información sobre tu servicio reservado.
        @else
          Una vez que ingresas el código de verificación podrás retirar.
        @endif
      </p>
    <br>
    <p>
      <b>ATENCIÓN</b>
      <br>
      El código está disponible solo por 15 min. Si no hiciste esta solicitud, restablezca tu contraseña. Si tiene
      alguna pregunta pongase en contacto con nuestro servicio de <a href="{{ route('user.contact-us') }}">Atención al Cliente</a>.
    </p>

@stop