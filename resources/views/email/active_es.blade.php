@extends('email.layout-en')

@section('email-content')
  <p>Confirmar registro en {{SITE_NAME}}</b></p><br>
  
  <p>Hola <b>{{$user->name}},</p><br>
  
  <p>gracias por registrarse en <b>{{SITE_NAME}}</b>.</p><br>
  
  <p>Tu cuenta ha sido creada y debe ser activada antes de poder usarla.</p><br>
  
  <p>Para activarla haz clic en el botón:</p><br><br>
  
  <p><a href="{{ $active_link }}">
      <button class="view-btn hvr-pulse-grow load-categories" type="submit" value="button">Activa tu Cuenta
      </button>
    </a></p><br><br>
  
  <p>Se tienes problemas favor de copiar y pegar el enlace en la barra de navegación:</p><br>
  
  <p>{{ $active_link }}</p><br><br>
  
  <p>Después de la activación, puede iniciar sesión con la dirección de correo electrónico y la contraseña que eligió durante el
     registro.</p><br><br>
    
@stop
