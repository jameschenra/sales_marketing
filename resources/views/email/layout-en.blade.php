<!doctype html>
<html>
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{!! trans('main.sitename') !!}</title>
    <style>
        *{ -webkit-box-sizing:border-box; box-sizing:border-box;}
        body{ font-family: 'Helvetica Neue', Helvetica, Arial, sans-serif; color:#fff;}
        div, h1,h3,body,h2,p,table{ margin:0; padding:0;}
        ul,ol{ list-style:none; margin:0; padding:0}
        a{ text-decoration:none;}
        p, span {
            color: #3a3a3a;
        }

        button.view-btn {
            min-width: 150px;
            border-radius: 6px;
            min-height: 44px;
            display: block;
            color: #fff;
            font-size: 18px;
            line-height: 34px;
            border: none;
            cursor: pointer;
            transition: all 2s ease;
            padding: 1px 30px;
            margin: 0 auto;
        }

        button.view-btn {
            background: rgba(11, 101, 254, 1);
            background: -moz-linear-gradient(left, rgba(11, 101, 254,1) 30%, rgba(141,181,249,1) 100%);
            background: -webkit-gradient(left top, right top, color-stop(30%, rgba(11, 101, 254,1)), color-stop(100%, rgba(141,181,249,1)));
            background: -webkit-linear-gradient(left, rgba(11, 101, 254,1) 30%, rgba(141,181,249,1) 100%);
            background: -o-linear-gradient(left, rgba(11, 101, 254,1) 30%, rgba(141,181,249,1) 100%);
            background: -ms-linear-gradient(left, rgba(11, 101, 254,1) 30%, rgba(141,181,249,1) 100%);
            background: linear-gradient(to right, rgba(11, 101, 254,1) 30%, rgba(141,181,249,1) 100%);
            filter: progid:DXImageTransform.Microsoft.gradient( startColorstr='#31ca7b', endColorstr='#27d1dd', GradientType=1 );
        }

        button, select {
            text-transform: none;
        }

        .logo-th {
          text-align: left;
        }

        span.datetime {
            min-width: 215px;
            display: inline-block;
        }

        .text-center {
          text-align: center;
        }

        .content_center {
            margin: 0 auto;
            width: fit-content;
        }
        .btn-center {
            display: block;
        }

        .btn-center button {
            margin:0 auto;
        }

        .right-align {
          text-align: right;
          padding:15px 0 0;
          float:right;
          padding-right:20px;
        }
        .policy-links {
          text-align: center;
        }
        .policy-links p {
          display: inline-block;
          float: left;
          padding: 0 15px 0;
          width: 33.3%;
        }
        .social-links {
          text-align: center;
          display: table;
        }
        .social-links a {
          display: inline-flex;
        }
        tbody button {
          margin: 0 auto;
          float: none;
        }
        .social-links {
          display: flex;
          text-align: center;
        }
        .social-links ul {
          justify-content:center;
          text-align:center;
          margin: 0 auto;
        }
        .social-links ul li{
          display:inline-block;
          margin-left: 0;
        }
        .social-links ul li a{
          height: 32px;
        }

        @media only screen and (max-width: 575px) {
          .logo-th {
            text-align: center;
            padding-left: 0;
          }
          .right-align {
            text-align: left;
            padding:0;
            float:left;
            padding-left:20px;
          }
          .policy-links p {
            display: inline-block;
            float: none;
            padding: 0 15px;
            text-align: center;
            width: 100%;
          }
          tfoot td:nth-child(2n) {
            order: 2;
          }
        }
    </style>

    @yield('email-style')

</head>
<body>
<table width="100%" style="margin:0 auto; background-color:#fff; color:#666; padding:0; max-width:750px" cellspacing="0" cellpadding="0" border-collapse: collapse;>
    <thead>
    <tr>
        <th colspan="2">
            <div style=" padding-top:20px"></div>
        </th>
    </tr>
    <tr>
        <th class="logo-th text-center" colspan="4">
            <img src="{{ asset('img/app_image/weredy-email-logo.png') }}" alt="img" style="width:40%;">
        </th>
    </tr>
    <tr>
        <th colspan="2">
            <div style=" padding-top:20px"></div>
        </th>
    </tr>
    <tr>
        <th colspan="2">
            <div style="border-bottom: 2px solid #fff"></div>
        </th>
    </tr>
    <tr>
      <th collspan="4" style="padding: 0px 20px; background: #fff;">
        <div style="background: #EBEBEB; width: 100%; height: 1px;"></div>
      </th>
    </tr>
  </thead>
    <tbody>
    <tr>
        <td colspan="4" style="padding:15px 20px;">

            @yield('email-content')

        </td>
    </tr>
    <tr>
        <th colspan="2">
            <div style=" padding-top:40px"></div>
        </th>
    </tr>
    <tr>
      <th collspan="4" style="padding:15px 20px;">
        <div style="background: #EBEBEB; width: 100%; height: 1px;"></div>
      </th>
    </tr>
    <tr>
        <th colspan="2">
            <div style=" padding-top:20px"></div>
        </th>
    </tr>

    </tbody>
    <tfoot style="color: #3a3a3a;">
      <tr style="padding-bottom: 20px;">
        <td class="es-p20r social-links" esd-tmp-icon-type="facebook" colspan="4">
          <ul>
            <li>
              <a target="_blank" href="https://www.facebook.com/weredyofficial/">
                <img title="Facebook" src="{{ asset('img/app_image/weredy-facebook-email-icon.png') }}" alt="Fb" width="32">
              </a>
            </li>
            <li>
              <a target="_blank" href="https://twitter.com/weredyofficial">
                <img title="Twitter" src="{{ asset('img/app_image/weredy-twitter-email-icon.png') }}"
                alt="Tw" width="32">
              </a>
            </li>
            <li>
              <a target="_blank" href="https://www.instagram.com/weredyofficial/">
                <img title="Instagram" src="{{ asset('img/app_image/weredy-instagram-email-icon.png') }}" alt="Inst" width="32">
              </a>
            </li>
            <li>
              <a target="_blank" href="https://www.pinterest.com/weredyofficial/">
                <img title="Pinterest" src="{{ asset('img/app_image/weredy-pinterest-email-icon.png') }}" alt="P" width="32">
              </a>
            </li>
          </ul>
        </td>
      </tr>
      <tr>
        <td class="policy-links" colspan="4" style="padding:30px 0 20px 0; text-align: center">
            <p style="font-size:12px; padding-top:0px;color:#3a3a3a;">
                <b><a target="_blank" href="{{route('user.privacy')}}" style="color:#3a3a3a;">{{trans('main.Privacy Policy')}}</a></b>
            </p>
            <p style="font-size:12px; padding-top:0px;color:#3a3a3a;">
                <b><a target="_blank" href="{{route('user.terms')}}" style="color:#3a3a3a; ">{{trans('main.Terms & Conditions')}}</a></b>
            </p>
            <p style="font-size:12px; padding-top:0px;color:#3a3a3a;">
                <b><a target="_blank" href="{{route('user.contact-us')}}" style="color:#3a3a3a; ">{{trans('main.Contact and Support')}}</a></b>
            </p>
        </td>
      </tr>
      <tr>
        <td colspan="4">
          <p style="font-size:9px; text-align:justify; margin: 0;color:#3a3a3a; padding: 0px 20px 0px;"><i>
            {!! trans('main.email_layout_footer_text', [], (isset($lang) ? $lang : null)) !!}
          </i></p><br />

          <p style="font-size:9px;    text-align: center;
              margin: 0px 0 20px;color:#3a3a3a;">
                <span><b>Copyright © 2015-{{date('Y')}} </b> {!! trans('main.sitename') !!}
          </p>
        </td>
      </tr>
      <tr>
        <th collspan="4" style="padding:15px 20px;">
          <div style="background: #EBEBEB; width: 100%; height: 1px;"></div>
        </th>
      </tr>
    </tfoot>
</table>
</body>
</html>
