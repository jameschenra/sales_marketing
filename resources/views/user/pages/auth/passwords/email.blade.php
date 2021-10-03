@extends('user.layout.default')

@section('content')
<section class="bg-login d-flex align-items-center">
    <div class="container">
        <div class="row align-items-center justify-content-center">
            <div class="col-lg-5 col-md-6 mt-4 mt-sm-0 pt-2 pt-sm-0">
                <div class="card login-page bg-white shadow rounded border-0">
                    <div class="card-body">
                        <h4 class="card-title text-center">@lang('main.Did you forgot the password?')</h4>
                        <br>
                        <h8 class="card-title text-center">@lang('main.Enter your email to reset')</h8>
                        <form method="POST" action="{{ route('user.forgot-password.sendResetLinkEmail') }}" class="login-form mt-4">
                            @csrf

                            <div class="row">
                                <div class="col-lg-12">
                                    <div class="form-group position-relative">
                                        <label>@lang('main.Email Address') <span class="text-danger">*</span></label>

                                        <i data-feather="email" class="fea icon-sm icons"></i>
                                        <input type="email" name="email" class="form-control pl-5 @error('email') is-invalid @enderror"
                                            value="{{ old('email') }}" placeholder="@lang('main.Email')" required autofocus>

                                        @include('user.components.validation-error', ['field' => 'email'])
                                    </div>
                                </div>

                                <div class="col-lg-12 mb-0">
                                    <button class="btn btn-primary btn-block">@lang('main.Send Request')</button>
                                </div>

                                <div class="col-12 mt-4">
                                    <p class="mb-0 mt-3 d-inline-block"><a href="{{ route('user.auth.showLogin') }}" class="text-dark font-weight-bold">@lang('main.Login')</a></p>
                                    <p class="mb-0 mt-3 d-inline-block float-right"><a href="{{ route('user.auth.showSignup') }}" class="text-dark font-weight-bold">@lang('main.Sign Up')</a></p>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
                <!---->
            </div>
            <!--end col-->
        </div>
        <!--end row-->
    </div>
    <!--end container-->
</section>
<!--end section-->
@endsection
