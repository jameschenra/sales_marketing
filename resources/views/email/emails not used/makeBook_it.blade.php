@extends('email.layout-en')

@section('email-content')
  <div>
    <h2>Congratulazioni!</h2>
  </div>
  <div>
    <p>La tua prenotazione è stata confermata con successo su {!! trans('main.sitename') !!}</p>
    <table>
      <thead>
        <tr>
          <th>Nome del Servizio</th>
          <th>Ufficio</th>
          <th>Indirizzo</th>
          @if(!empty($book_date))
            <th>Data dell'appuntamento</th>
          @endif
          @if(!empty($duration))
            <th>Durata del Servizio</th>
          @endif
          @if(!empty($online_delivery_time))
            <th>Giorni per la consegna</th>
          @endif
          <th>Totale</th>
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
    <b>ATTENZIONE<br>
      Nel caso in cui decidi di disdire o modificare l´appuntamento potrai farlo o contattando direttamente per telefono
      il Professionista o accedendo alla tua area riservata nella sezione appuntamenti.<br><br>
  </div>
  
@stop
