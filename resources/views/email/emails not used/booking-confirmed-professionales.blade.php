@extends('email.layout-en')
@section('email-content')
    <p>
        Hola {{ $book->user->name }},
    </p>
    <p>
        <strong>Nueva cita por:</strong> {{ $book->user->name ." ".$book->user->surname  }}
    </p>
    <p>
        <strong>Por el servicio:</strong> {{ $book->service->{"name_{$locale}"} }}
    </p>
    <p>
        <strong>Fecha de la cita:</strong> {{ $book->book_date }}
    </p>
    <p>
        <strong>En:</strong> {{ $book->user_address ? 'Offsite' : $book->office->name }}
    </p>
    <p>
        <strong>Direccion:</strong> {{ $book->office->address or $book->user_address }}
    </p>
    <p>
        <strong>Telefono oficina:</strong> {{ $book->office->telephone }}
    </p>
    @if(!empty($isPaidOnline) && $isPaidOnline)
        <p>
            <strong>Precio pagado en línea en {!! trans('main.sitename') !!}:</strong> €{{ $book->price }}
        </p>
    @else
        <p>
            <strong>Precio a pagar en la fechas de la cita:</strong> €{{ $book->price }}
        </p>
    @endif
    <br>
    <p>
        Datos de contacto de {{ $book->user->name ." ".$book->user->surname  }}
    </p>
    <p>
        <strong>Telefono:</strong> {{ $book->user->phone }}
    </p>
    <p>
        <strong>Correo electrónico:</strong> {{ $book->user->email }}
    </p>
    <p>
        CUIDADO
    </p>
    <p>
        Le recordamos que si usted necesita cancelar la cita puede hacerlo llamando al Profesional o desde su panel de configuracion en la seccion de las citas.
    </p>
@stop