@extends('email.layout-en')

@section('email-content')
  <div>
    <h2>¡Felicidades</h2>
  </div>
  <div>
    <p>Un usuario ha reservado tu servicio {{ $store_name }} service</p>
    <table>
      <thead>
        <tr>
          <th>Nombre del Servicio</th>
          <th>Oficina</th>
          <th>Dirección</th>
          <th>Nombre Usuario</th>
          @if(!empty($book_date))
            <th>Fecha de la reserva</th>
          @endif
          @if(!empty($duration))
            <th>Duración del Servicio</th>
          @endif
          @if(!empty($online_delivery_time))
            <th>Días para la entrega</th>
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

    <p>Mensaje : {{ $msg }}</p>
  </div>
  <hr/>
  <div>
    <b>Saludos,<br>
      <b>El equipo de {!! trans('main.sitename') !!} <br><br>
        Este mensaje fue enviado por {!! trans('main.sitename') !!} y puede contener información confidencial. En caso
        de que no es el destinatario, por favor notifique inmediatamente a la misma direccion y borre el mensaje y sus
        adjuntos, sin retener una copia. Cualquier uso no autorizado del contenido de este mensaje es una violación de
        la obligación de no tomar conocimiento de la correspondencia entre otras partes, excepto para el delito más
        grave, y expone el gerente de las consecuencias legales pertinentes.
  </div>
@stop
