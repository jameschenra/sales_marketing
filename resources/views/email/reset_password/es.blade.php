@extends('email.layout-en')

@section('email-content')

  <p>Hemos recibido una solicitud para establecer una nueva contraseña. Para restablecer tu contraseña, haga clic en el botón:<p><br><br>

  <p><a href="{{ $reset_link }}">
      <button class="view-btn hvr-pulse-grow load-categories" type="submit" value="button">Resetear tu contraseña
      </button>
    </a></p><br><br>

  <p>Si no solicitó un cambio de contraseña, ignore este correo electrónico.</p>

@stop