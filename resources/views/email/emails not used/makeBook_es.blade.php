@extends('email.layout-en')

@section('email-content')
  <div>
    <h2>¡Felicidades</h2>
  </div>
  <div>
    <p>Su reserva ha sido confirmada con éxito en {!! trans('main.sitename') !!}</p>
    <table>
      <thead>
        <tr>
          <th>Nombre del Servicio</th>
          <th>Oficina</th>
          <th>Dirección</th>
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
    <p>Mensaje : {{ $msg }}</p>
  </div>
  <hr/>
  <div>
    <b>CUIDADO<br>
      Le recordamos que si usted necesita cancelar la cita puede hacerlo llamando al Profesional o desde su panel de
      configuracion en la seccion de las citas.<br><br>
  </div>
  
@stop
