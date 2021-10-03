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
        {{-- ------------- header ------------ --}}
        <center>
            <p class="invite-heading">The website for professionals online. <br />Get anywhere with <span style="color: #0B65FE"><b>We</b></span><span style="color:#00274C"><b>red</b></span><span style="color:#0B65FE"><b>y</b></span>.
            </p>
            <img src="{{ asset('img/email/Invite-weredy-header.png') }}" alt="img" style="width:80%;">
            <p class="invite-heading">Find new customers wherever you want, using a flexible and comprehensive online website.</p>
        </center>
        {{-- -------------./ header ---------- --}}

        <div class="split-line"></div>

        {{-- ------------- content ------------ --}}
        <div class="invite-content">
            {{-- section 1 --}}
            <div class="section-wrapper">
                <div class="image-wrapper float-left">
                    <img src="{{ asset('img/email/Invite-icon-1.png') }}" alt="img">
                </div>

                <div class="content-wrapper">
                    <p class="section-title">Your best showcase</p>
                    <p class="section-content">
                        <strong>Create your profile</strong>, we will help you in the best way to show your new customers what you can do. <br />
            <strong>Upload the services</strong> you offer, set your rates, the location (even online) and the service duration:<br /> 
            <span style="color:#0B65FE"><b>avoid any misunderstanding!</b></span>
                    </p>
                </div>
            </div>
            {{-- ./ section 1 --}}

            <div class="clearfix m-b-20"></div>

            {{-- section 2 --}}
            <div class="section-wrapper">
                <div class="image-wrapper float-right">
                    <img src="{{ asset('img/email/Invite-icon-2.png') }}" alt="img">
                </div>

                <div class="content-wrapper">
                    <p class="section-title">The flexibility you need</p>
                    <p class="section-content">
                        <strong>Mostra il tuo calendario</strong>, enter your availability for dates and times and receive reservations with or without the possibility of your confirmation, you can choose it! <br />
          <span style="color:#0B65FE"><b>Manage appointments in your personal area.</b></span>
                    </p>
                </div>
            </div>
            {{-- ./ section 2 --}}

            <div class="clearfix m-b-20"></div>

            {{-- section 3 --}}
            <div class="section-wrapper">
                <div class="image-wrapper float-left">
                    <img src="{{ asset('img/email/Invite-icon-3.png') }}" alt="img">
                </div>

                <div class="content-wrapper">
                    <p class="section-title">A community beyond work</p>
                    <p class="section-content">
                        <strong>Grow with the support of your customers</strong>, 
            make yourself known by <span style="color:#0B65FE">writing articles for the blog</span> and always count on our team!
                    </p>
                </div>
            </div>
            {{-- ./ section 3 --}}
        </div>
        {{-- -------------./ content ------------ --}}

        <div class="clearfix split-line m-b-20"></div>

        {{-- ------------- footer ------------ --}}
        <div class="invite-footer">
            <div class="footer-img-wrapper float-left">
                <img src="{{ asset('img/email/Invite-weredy-footer.png') }}" alt="img">
            </div>

            <div class="footer-content content-wrapper">
                <p class="section-content">
                    <strong><span style="color: #0B65FE"><b>We</b></span><span style="color:#00274C"><b>red</b></span><span style="color:#0B65FE"><b>y</b></span> it has no cost</strong>.<br /> 
          <strong>You'll have your virtual wallet</strong> where to manage income and expenses, you can also choose whether to get paid online or in cash later. <br /> 
          You only pay if the customer buys<br /> 
          No registration costs, you only pay a fee for each order received.
                </p>
                <p class="section-content footer-description">
                </p>
            </div>

            <div class="clearfix split-line m-b-20 m-t-30"></div>

            <div class="content-wrapper">
                <p class="section-content">
                    <strong>This is an informative e-mail</strong> sent through a request of
                    <strong>{{ $user->name }}</strong> to let you know about our website.<br />
                    <strong>{{ $name }}</strong>, we would like to know if you think this could be interesting for
                    you. Remember that by signing up you have no restrictions, no monthly or annual fee and you can unsubscribe at any time without any cost.<br />
                    <strong>What do you think about it? Do you want to sign up?</strong>
                </p>
            </div>

            <div class="action-wrapper">
                <div class="button-wrapper">
                    <a class="action-link accept-link"
                        href="{{ route('user.invite.signup', ['sender' => $sender, 'email' => $email, 'accept' => '1']) }}">Yes,
                        I want to sign up</a>
                </div>
                <div class="button-wrapper">
                    <a class="action-link reject-link"
                        href="{{ route('user.invite.signup', ['sender' => $sender, 'email' => $email, 'accept' => '0']) }}">I
                        would like more informations</a>
                </div>
            </div>
        </div>
        {{-- -------------./ footer ---------- --}}
    </div>

@stop
