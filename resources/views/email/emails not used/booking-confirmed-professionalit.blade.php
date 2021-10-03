@extends('email.layout-en')
@section('email-content')
    <?php $locale == 'en' ? '' : $locale ?>
    <p>
        Ciao {{ $book->company->name }},
    </p>
    <p>
        <strong>Nuovo appuntamento da:</strong> {{ $book->user->name." ".$book->user->surname }}
    </p>
    <p>
        <strong>Per il servizio:</strong> {{ $book->store->{"name{$locale}"} }}
    </p>
    <p>
        <strong>Data dell'appuntamento:</strong> {{ $book->book_date }}
    </p>
    <p>
        <strong>Presso:</strong> {{ $book->user_address ? 'Fuori sede' : $book->office->name }}
    </p>
    <p>
        <strong>Indirizzo:</strong> {{ $book->office->address or $book->user_address }}
    </p>
    <p>
        <strong>Telefono Ufficio:</strong> {{ $book->office->telephone }}
    </p>
    @if(!empty($isPaidOnline) && $isPaidOnline)
        <p>
            <strong>Prezzo pagato online su {!! trans('main.sitename') !!}:</strong> €{{ $book->price }}
        </p>
    @else
        <p>
            <strong>Prezzo da pagare il giorno dell'appuntamento:</strong> €{{ $book->price }}
        </p>
    @endif
    <br>
    <p>
        Contatti di {{ $book->user->name ." ".$book->user->surname}}
    </p>
    <p>
        <strong>Telefono:</strong> {{ $book->user->phone }}
    </p>
    <p>
        <strong>Email:</strong> {{ $book->user->email }}
    </p>
    <p>
        ATTENZIONE
    </p>
    <p>
        Le ricordiamo che, qualora non possa espletare il servizio richiesto nelle date scelte dal cliente, la preghiamo di contattare quest’ultimo con congruo anticipo anche telefonicamente o a mezzo mail.  La invitiamo altresì ad aggiornare le nuove disponiblità nella sua area riservata.
    </p>
@stop