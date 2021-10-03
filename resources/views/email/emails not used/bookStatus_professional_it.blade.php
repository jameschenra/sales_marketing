@extends('email.layout-en')

@section('email-content')
  @php
    $lang = "it";
    $oldLang = \App::getLocale();
    \App::setLocale($lang);
  @endphp

  <div class="content_center">

    <p>
      Ciao <b>{{ $book->user->name." ".$book->user->surname }}</b>,</p><br>

    <p>
      <b>{{ $book->user->name.' '.$book->user->surname }} ha cancellato il suo appuntamento.</b>
    </p>
    <br>

    <p>
      Di seguito i dettagli del servizio che cancellato:
    </p>

    <p>
      <b>Cliente:</b> {{ $book->user->name.' '.$book->user->surname }}
    </p>
    <p>
      <b>Servizio:</b> {{ $book->service->name }}
    </p>
    <p>
      <b>Ordine numero:</b> {{ $book->id }}
    </p>
    @php
      $date_book = new DateTime($book->book_date);
      $date_order = new DateTime($book->created_at);

    @endphp
    <p>
      <span
          class="datetime"><b>Data dell’ordine:</b> {{ $date_order->format('d-m-Y') }}</span>
      <br>
      <b>Orario:</b> {{ $date_order->format('H:i') }}
    </p>
    <br>
    <p>
      <span
          class="datetime"><b>Data della prenotazione:</b> {{ $date_book->format('d-m-Y') }}</span>
      <br>
      <b>Orario:</b> {{ $date_book->format('H:i') }}
    </p>
    <p>
      <b>Durata:</b> {{ $book->prettyDuration }}
    </p>
    <p>
      <b>Numero di servizi prenotati:</b> {{ $book->number_of_booking }}
    </p>
    <br>

    <p>
      @if($book->is_paid_online == 1 && $book->price > 0)
        <b>Prezzo che hai rimborsato:</b> €{{ number_format($book->price, 2) }}
      @endif

      @if($book->is_paid_online == 0 && $book->price > 0)
        <b>Prezzo che il cliente doveva pagare:</b> €{{ number_format($book->price, 2) }}
      @endif

      @if($book->price == 0)
        <b>Prezzo pagato:</b> Servizio gratuito
      @endif
    </p>

    @if(!empty($bookingTransaction))
      <p>
        <b>Transazione numero:</b> {{$bookingTransaction->id}}
      </p>
    @endif

    @if(!empty($feeTransaction))
      <p>
        <b>Transazione per fee:</b> {{$feeTransaction->id}}
      </p>
    @endif
    <br>

    <p>
      <b>Luogo dove doveva essere fornito il
        servizio:</b> {{ $book->user_address ? trans('main.Customers Address') : $book->office->name }}
    </p>

    <p>
      <b>Indirizzo:</b> {{ $book->user_address ? $book->user_address : $book->office->country->short_name.' '.$book->office->region->name.' '.$book->office->city->name.' '.$book->office->address }}
    </p>

    <p>
      <b>Telefono della sede:</b> {{ $book->office->telephone }}
    </p><br>

    <p>
      <b>Informazioni di contatto di</b> {{ $book->user->name.' '.$book->user->surname }}
    </p>
    <p><b>Telefono:</b> {{ $book->user->phone }}</p>
    <p>
      <b>Email:</b> {{ $book->user->email }}
    </p><br>

    @if(!empty($book->message))
      <p>
        <b>Messaggio inviato durante la prenotazione:</b><br>{{ $book->message }}
      </p><br>
    @endif

    <p>
      <b>ATTENZIONE</b>
      <br>
      Ti ricordiamo che, secondo i nostri <b><a href="{{route('user.terms')}}">Termini e Condizioni</a></b>, una volta annullato il servizio prenotato,
      rimborserai automaticamente il tuo cliente e ti verrà trattenuta una fee per le spese di gestione.
    </p>
  </div>

  @php
    \App::setLocale($oldLang);
  @endphp
@stop