@extends('email.layout-en')

@section('email-style')
  <style>
    .float-left {
      float: left;
    }

    .float-right {
      float: right;
    }

    .clearfix {
      clear: both;
    }

    .split-line {
      background: #EBEBEB;
      width: 100%;
      height: 1px;
    }

    .m-t-30 {
      margin-top: 30px;
    }

    .m-b-20 {
      margin-bottom: 20px;
    }
    
    strong {
      color: #3a3a3a
    }

    .invite-heading {
      font-size: 28px;
      margin-top: 20px;
    }

    .how-it-work {
      font-size: 40px;
      font-weight: 600;
      color: #0B65FE;
      margin-bottom: 30px;
    }

    .invite-content {
      margin-top: 35px;
    }

    .image-wrapper {
      width: 25%;
    }

    .image-wrapper img {
      width: 100%;
    }

    .section-title {
      font-size: 25px;
      font-weight: 600;
      margin-bottom: 8px;
    }

    .section-content {
      font-size: 17px;
      color: #999;
      line-height: 1.5;
    }

    .footer-img-wrapper {
      width: 50%;
      padding-right: 10px;
    }

    .footer-img-wrapper img {
      width: 100%;
    }

    .action-wrapper {
      margin-top: 40px;
    }

    a.action-link {
      text-align: center;
      color: #0B65FEFF;
      font-size: 18px;
      font-weight: 700;
      padding: 10px 30px;
      border: 1px solid #0B65FEFF;
      border-radius: 25px;
      display: block;
      width: 255px;
      transition: all 0.6s;
    }

    a.action-link:hover {
      color: #FFF;
      background-color: #0B65FEFF;
    }

    a.accept-link {
      float: left;
      margin-left: 20px;
    }

    a.reject-link {
      float: right;
      margin-right: 25px;
    }

    @media (max-width: 600px) {
      a.accept-link {
        float: none;
        margin: 10px auto;
      }

      a.reject-link {
        float: none;
        margin: 0 auto;
      }
    }

  </style>
@stop

@section('email-content')
<div>
  {{--------------- header --------------}}
  <center>
    <p class="invite-heading">El sitio web de los profesionales en línea. <br />Llega a todas partes con <span style="color: #0B65FE"><b>We</b></span><span style="color:#00274C"><b>red</b></span><span style="color:#0B65FE"><b>y</b></span>.</p>
    <img src="{{ asset('img/email/Invite-weredy-header.png') }}" alt="img" style="width:80%;">
    <p class="invite-heading">Encuentra nuevos clientes donde quiera, utilizando un portal en línea flexible y completo.</p>
  </center>
  {{---------------./ header ------------}}

  <div class="split-line"></div>

  {{--------------- content --------------}}
  <div class="invite-content">
    {{-- section 1 --}}
    <div class="section-wrapper">
      <div class="image-wrapper float-left">
        <img src="{{ asset('img/email/Invite-icon-1.png') }}" alt="img">
      </div>

      <div class="content-wrapper">
        <p class="section-title">Tu mejor escaparate</p>
        <p class="section-content">
          <strong>Crea tu perfil</strong>, Te guiaremos de la mejor manera para mostrarles a tus nuevos clientes lo que puedes hacer. <br />
            <strong>Sube los servicios</strong> que ofreces, establezca las tarifas, la ubicación (incluso en línea) y la duración del servicio:<br /> 
            <span style="color:#0B65FE"><b>¡evite cualquier malentendido!</b></span>
        </p>
      </div>
    </div>
    {{--./ section 1 --}}

    <div class="clearfix m-b-20"></div>

    {{-- section 2 --}}
    <div class="section-wrapper">
      <div class="image-wrapper float-right">
        <img src="{{ asset('img/email/Invite-icon-2.png') }}" alt="img">
      </div>

      <div class="content-wrapper">
        <p class="section-title">La flexibilidad que necesitas</p>
        <p class="section-content">
          <strong>Muestra tu calendario</strong>, ingresa tu disponibilidad por fechas y horarios y recibe reservas con o sin posibilidad de tu confirmación, ¡puedes elegirlo! <br />
          <span style="color:#0B65FE"><b>Gestiona citas en tu área personal.</b></span>
        </p>
      </div>
    </div>
    {{--./ section 2 --}}

    <div class="clearfix m-b-20"></div>

    {{-- section 3 --}}
    <div class="section-wrapper">
      <div class="image-wrapper float-left">
        <img src="{{ asset('img/email/Invite-icon-3.png') }}" alt="img">
      </div>

      <div class="content-wrapper">
        <p class="section-title">Una comunidad más allá del trabajo</p>
        <p class="section-content">
          <strong>¡Crece con el apoyo de tus clientes</strong>, 
            date a conocer <span style="color:#0B65FE">escribiendo artículos para el blog</span> y cuenta siempre con nuestro equipo!
        </p>
      </div>
    </div>
    {{--./ section 3 --}}
  </div>
  {{---------------./ content --------------}}

  <div class="clearfix split-line m-b-20"></div>

  {{--------------- footer --------------}}
  <div class="invite-footer">
    <div class="footer-img-wrapper float-left">
      <img src="{{ asset('img/email/Invite-weredy-footer.png') }}" alt="img">
    </div>

    <div class="footer-content content-wrapper">
      <p class="section-content">
        <strong><span style="color: #0B65FE"><b>We</b></span><span style="color:#00274C"><b>red</b></span><span style="color:#0B65FE"><b>y</b></span> no cuesta nada</strong>.<br /> 
          <strong>Tendrás tu propia billetera virtual</strong> dónde administrar los ingresos y los gastos, también puedes elegir si deseas recibir el pago en línea o en efectivo más tarde. <br /> 
          Solo pagas si el cliente compra<br /> 
          Sin costos de registro, solo paga una tarifa por cada pedido recibido.
      </p>
      <p class="section-content footer-description">
      </p>
    </div>

    <div class="clearfix split-line m-b-20 m-t-30"></div>

    <div class="content-wrapper">
      <p class="section-content">
        <strong>Este es un correo electrónico informativo</strong> y se envió a solicitud de <strong>{{$user->name}}</strong> para informarte sobre nuestro sitio web.<br />
        <strong>{{$name}}</strong>, nos gustaría saber si cree que esto podría ser interesante para ti. Recuerda que al registrarse, no tienes restricciones, no tienes cuotas mensuales o anuales y puedes darle de baja en cualquier momento sin ningún costo.<br />
        <strong>¿Qué dices? ¿Quieres registrarte?</strong>
      </p>
    </div>

    <div class="action-wrapper">
      <div class="button-wrapper">
        <a class="action-link accept-link" href="{{ route('user.invite.signup', ['sender' => $sender, 'email' => $email, 'accept' => '1']) }}">Si, quiero registrarme</a>
      </div><div class="button-wrapper">
        <a class="action-link reject-link" href="{{ route('user.invite.signup', ['sender' => $sender, 'email' => $email, 'accept' => '0']) }}">Quisiera más informaciónes</a>
      </div>
    </div>
  </div>
  {{---------------./ footer ------------}}
</div>

@stop