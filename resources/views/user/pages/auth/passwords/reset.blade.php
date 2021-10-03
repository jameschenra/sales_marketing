@extends('user.layout.default')

@section('content')
<section class="bg-login d-flex align-items-center">
    <div class="container">
        <div class="row align-items-center justify-content-center">
            <div class="col-lg-5 col-md-6 mt-4 mt-sm-0 pt-2 pt-sm-0">
                <div class="card login-page bg-white shadow rounded border-0">
                    <div class="card-body">
                        <h4 class="card-title text-center">@lang('main.Reset Your Password')</h4>
                        <br>
                        <h8 class="card-title text-center">@lang('main.Enter New Password')</h8>
                        <form method="POST" action="{{ route('user.forgot-password.reset') }}" class="login-form mt-4">
                            @csrf

                            <input type="hidden" name="token" value="{{ $token }}" />

                            <div class="row">
                                <div class="col-lg-12">
                                    <div class="form-group">
                                        <label>@lang('main.Email') <span class="text-danger">*</span></label>

                                        <input type="email" name="email" class="form-control pl-5 @error('email') is-invalid @enderror"
                                            value="{{ old('email') }}" placeholder="@lang('main.Email')" required autofocus>

                                        @error('email')
                                            <span class="invalid-feedback" role="alert">
                                                <strong>{{ $message }}</strong>
                                            </span>
                                        @enderror
                                    </div>
                                </div>

                                <div class="col-lg-12">
                                    <div class="form-group">
                                        <label>@lang('main.New Password') <span class="text-danger">*</span></label>

                                        <input type="password" name="password" class="form-control pl-5 @error('password') is-invalid @enderror"
                                            value="{{ old('password') }}" placeholder="@lang('main.New Password')" required autofocus>

                                        @error('password')
                                            <span class="invalid-feedback" role="alert">
                                                <strong>{{ $message }}</strong>
                                            </span>
                                        @enderror
                                    </div>
                                </div>

                                <div class="col-lg-12">
                                    <div class="form-group">
                                        <label>@lang('main.Retype Password') <span class="text-danger">*</span></label>

                                        <input type="password" name="password_confirmation" class="form-control pl-5 @error('password_confirmation') is-invalid @enderror"
                                            value="{{ old('password_confirmation') }}" placeholder="@lang('main.Retype Password')" required autofocus>

                                        @error('password_confirmation')
                                            <span class="invalid-feedback" role="alert">
                                                <strong>{{ $message }}</strong>
                                            </span>
                                        @enderror
                                    </div>
                                </div>

                                <div class="col-lg-12 mb-0">
                                    <button class="btn btn-primary btn-block">@lang('main.Update-Password-button')</button>
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
