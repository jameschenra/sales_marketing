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
    <p class="invite-heading">Il portale dei professionisti online. <br />Arriva ovunque con <span style="color: #0B65FE"><b>We</b></span><span style="color:#00274C"><b>red</b></span><span style="color:#0B65FE"><b>y</b></span>.</p>
    <img src="{{ asset('img/email/Invite-weredy-header.png') }}" alt="img" style="width:80%;">
      <p class="invite-heading">Trova nuovi clienti dove vuoi, utilizzando un portale online flessibile e completo.</p>
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
        <p class="section-title">La tua miglior vetrina</p>
        <p class="section-content">
            <strong>Crea il tuo profilo</strong>, ti guideremo al meglio per mostrare ai tuoi nuovi clienti cosa sai fare. <br />
            <strong>Carica i servizi</strong> che offri e potrai stabilire subito le tariffe, il luogo, anche online, e la durata del servizio:<br /> 
            <span style="color:#0B65FE"><b>evita qualsiasi fraintendimento!</b></span>
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
        <p class="section-title">La flessibilità di cui hai bisogno</p>
        <p class="section-content">
          <strong>Mostra il tuo calendario</strong>, inserisci le tue disponibilità per le date e gli orari e ricevi prenotazioni con o senza possibilità di una tua conferma, questo lo scegli tu! <br />
          <span style="color:#0B65FE"><b>Gestisci gli appuntamenti nella tua area personale.</b></span> 
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
        <p class="section-title">Una community oltre al lavoro</p>
        <p class="section-content">
          <strong>Cresci col supporto dei tuoi clienti</strong> 
            fatti conoscere <span style="color:#0B65FE">scrivendo articoli per il blog</span> e conta sempre sul nostro team!
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
        <strong><span style="color: #0B65FE"><b>We</b></span><span style="color:#00274C"><b>red</b></span><span style="color:#0B65FE"><b>y</b></span> non ti costa nulla</strong>.<br /> 
          <strong>Avrai il tuo portafoglio virtuale</strong> dove gestire entrate e uscite, inoltre potrai scegliere se farti pagare subito online o dopo in contanti. <br /> 
          Paghi solo se il cliente acquista<br /> 
          Niente costi di iscrizione, paghi solo una fee ad ogni ordine ricevuto.
      </p>
      <p class="section-content footer-description">
      </p>
    </div>

    <div class="clearfix split-line m-b-20 m-t-30"></div>

    <div class="content-wrapper">
      <p class="section-content">
        <strong>Questa è una e-mail informativa</strong> ed è stata inviata su richiesta di <strong>{{$user->name}}</strong> allo scopo di farti conoscere il nostro portale.<br/>
        <strong>{{$name}}</strong>, ci piacerebbe sapere se ritieni questo possa essere interessante per te. Ti ricordiamo che iscrivendoti non hai nessun vincolo, nessuna quota mensile o annuale e potrai cancellare l’iscrizione in qualsiasi momento senza nessun costo.<br/>
        <strong>Cosa aspetti? Iscriviti adesso!</strong>
      </p>
    </div>

    <div class="action-wrapper">
      <div class="button-wrapper">
        <a class="action-link accept-link" href="{{ route('user.invite.signup', ['sender' => $sender, 'email' => $email, 'accept' => '1']) }}">Si, voglio iscrivermi</a>
      </div><div class="button-wrapper">
        <a class="action-link reject-link" href="{{ route('user.invite.signup', ['sender' => $sender, 'email' => $email, 'accept' => '0']) }}">Voglio avere maggiori informazioni</a>
      </div>
    </div>
  </div>
  {{---------------./ footer ------------}}
</div>

@stop