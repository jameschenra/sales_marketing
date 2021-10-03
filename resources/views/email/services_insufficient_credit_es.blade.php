@extends('email.layout-en')

@section('email-content')

  <p>Hola <b>{{ $name }}</b>,</p>
  
  <p>Tus servicios gratuitos no se mostrarán y la posibilidad de pagar en la oficina no estará disponible 
     por crédito insuficiente.</p><br>

  <p>Para permitir que tus clientes vean tus servicios gratuitos y que reserven y paguen
     directamente en tus oficinas, debe tener un saldo mínimo disponible de €{{ MINIMUM_BALANCE }},00. 
     No se retirará ninguna cantidad hasta que tus clientes hagan ordenes o reservas. Por
     cada servicio ordenado o reservado, se cobrará una fee de €{{FEE}},00. Puedes agregar
     crédito ahora desde tu página del <b><a href="{{route('user.balance.show')}}">Saldo</a></b>.</p><br><br>

  <p>Puedes desactivar el pago en la oficina y la publicación de servicios gratuitos desde la página de los <b><a href="{{ URL::route('company.profile', 'settings') }}">Ajustes</a></b>.</p>

@stop