@php
    $isFromProfessional = session('professional_email') ? 0 : 1;
@endphp

@extends('user.layout.default')

@section('styles')
    @include('user.include-plugins.input-tel.input-tel-css')
    <style>
        #kt_content {
            min-height: 1024px;
        }
    </style>
    
@endsection

@section('content')
<section class="bg-home d-flex bg-register">
    <div class="container">
        <div class="row align-items-center justify-content-center">
            <div class="col-lg-7 col-md-7 mt-4 mt-sm-0 pt-2 pt-sm-0">
                <div class="card login_page shadow rounded border-0">
                    <div class="card-body">
                        <h4 class="card-title text-center">@lang('main.Signup title')</h4>
                        <!-- Sign Up Form -->
                        <form method="POST" action="{{ route('user.auth.signup') }}" class="login-form mt-4">
                            @csrf

                            <div class="row">
                                <!-- first name -->
                                <div class="col-md-6">
                                    <div class="form-group position-relative">
                                        <label>@lang('main.Name') <span class="text-danger">*</span></label>

                                        <i data-feather="user" class="fea icon-sm icons"></i>
                                        <input type="text" name="name" id="name" class="form-control pre-icon @error('name') is-invalid @enderror"
                                            value="{{ old('name') }}" placeholder="@lang('main.Name')" autocomplete="off" autofocus required>

                                        @error('name')
                                            <span class="invalid-feedback" role="alert">
                                                <strong>{{ $message }}</strong>
                                            </span>
                                        @enderror
                                    </div>
                                </div>
                                <!--./ first name -->

                                <!-- last name -->
                                <div class="col-md-6">
                                    <div class="form-group position-relative">
                                        <label>@lang('main.Surname') <span class="text-danger">*</span></label>

                                        <i data-feather="user-check" class="fea icon-sm icons"></i>
                                        <input type="text" name="last_name" id="last_name" class="form-control pre-icon @error('last_name') is-invalid @enderror"
                                            value="{{ old('last_name') }}" placeholder="@lang('main.Surname')" autocomplete="off" required>

                                        @error('last_name')
                                            <span class="invalid-feedback" role="alert">
                                                <strong>{{ $message }}</strong>
                                            </span>
                                        @enderror
                                    </div>
                                </div>
                                <!--./ last name -->

                                <!-- email -->
                                <div class="col-md-6">
                                    <div class="form-group position-relative">
                                        <label>@lang('main.Email') <span class="text-danger">*</span></label>

                                        <i data-feather="mail" class="fea icon-sm icons"></i>
                                        <input type="email" name="email" id="email" class="form-control pre-icon @error('email') is-invalid @enderror"
                                            value="{{ old('email', session('professional_email', '')) }}" placeholder="@lang('main.Email')" autocomplete="off" required>

                                        @error('email')
                                            <span class="invalid-feedback" role="alert">
                                                <strong>{{ $message }}</strong>
                                            </span>
                                        @enderror
                                    </div>
                                </div>
                                <!--./ email -->

                                <!-- phone -->
                                <div class="col-md-6">
                                    <div class="form-group position-relative">
                                        <label>@lang('main.Phone') <span class="text-danger">*</span></label>

                                        <input type="text" name="phone" id="phone" class="input-decimal form-control @error('phone') is-invalid @enderror"
                                            value="{{ old('phone') }}" placeholder="@lang('main.Phone')" autocomplete="off" required>

                                        @error('phone')
                                            <span class="invalid-feedback d-block" role="alert">
                                                <strong>{{ $message }}</strong>
                                            </span>
                                        @enderror
                                    </div>
                                </div>
                                <!--./ phone -->

                                <!-- password -->
                                <div class="col-md-6">
                                    <div class="form-group position-relative">
                                        <label>@lang('main.Password') <span class="text-danger">*</span></label>

                                        <i data-feather="key" class="fea icon-sm icons"></i>
                                        <input type="password" name="password" value="{{ session('professional_password', '') }}" id="password" class="form-control pre-icon @error('password') is-invalid @enderror"
                                            placeholder="@lang('main.Password')" autocomplete="new-password" required>

                                        @error('password')
                                            <span class="invalid-feedback d-block" role="alert">
                                                <strong>{{ $message }}</strong>
                                            </span>
                                        @enderror
                                    </div>
                                </div>
                                <!--./ password -->

                                <div class="col-md-6">
                                    <div class="form-group position-relative">
                                        <label>@lang('main.Password Confirmation') <span class="text-danger">*</span></label>

                                        <i data-feather="key" class="fea icon-sm icons"></i>
                                        <input type="password" name="password_confirmation" value="{{ session('professional_password', '') }}" id="password-confirm" class="form-control pre-icon"
                                            placeholder="@lang('main.Password Confirmation')" required autocomplete="new-password">
                                    </div>
                                </div>

                                <div class="col-md-12">
                                    <div class="form-group">
                                        <div><label>@lang('main.signup.i.want.to'): <span class="text-danger">*</span></label></div>

                                        <div class="custom-control custom-radio custom-control-inline ml-3 mb-2">
                                            <div class="form-group mb-0">
                                                <input type="radio" id="type-seller" name="type" value="0" class="custom-control-input" {{ (old('type', $isFromProfessional) == '0') ? 'checked' : ''}}>
                                                <label class="custom-control-label" for="type-seller">@lang('main.signup.become.seller')</label>
                                            </div>
                                        </div>
                                        <div class="custom-control custom-radio custom-control-inline ml-3">
                                            <div class="form-group mb-0">
                                                <input type="radio" id="type-buyer" name="type" value="1" class="custom-control-input" {{ (old('type') == '1') ? 'checked' : ''}}>
                                                <label class="custom-control-label" for="type-buyer">@lang('main.signup.become.buyer')</label>
                                            </div>
                                        </div>

                                        @error('type')
                                            <span class="invalid-feedback d-block" role="alert">
                                                <strong>{{ $message }}</strong>
                                            </span>
                                        @enderror
                                    </div>
                                </div>

                                <div class="split mt-0"></div>

                                <div class="col-md-12">
                                    <div class="form-group">
                                        <label>@lang('main.By registering you confirm that you accept the') <a href="#" class="text-primary">@lang('main.Terms & Conditions')</a> @lang('main.and')  <a href="#" class="text-primary">@lang('main.Privacy Policy').</a></label>
                                    </div>
                                </div>
                                <div class="col-md-12">
                                    <div class="form-group">
                                        <div class="custom-control custom-checkbox">
                                            <input type="checkbox" name="agree_privacy" id="agree-terms" value="1"
                                                class="custom-control-input" @if(old('agree_privacy')) checked @endif>
                                            <label class="custom-control-label" for="agree-terms">@lang('main.signup.consents.if.adult.text')
                                            </label>
                                        </div>
                                    </div>
                                </div>

                                <div class="col-md-12">
                                    <div class="form-group">
                                        <div class="custom-control custom-checkbox">
                                            <input type="checkbox" name="agree_data" id="agree-data" value="1"
                                                class="custom-control-input" @if(old('agree_data')) checked @endif>
                                            <label class="custom-control-label" for="agree-data">@lang('main.signup.consents.data.searching.purchases.new.services')
                                            </label>
                                        </div>
                                    </div>
                                </div>

                                <div class="col-md-12">
                                    <div class="form-group">
                                        <div class="custom-control custom-checkbox">
                                            <input type="checkbox" name="agree_update" id="agree-update" value="1"
                                                class="custom-control-input" @if(old('agree_update')) checked @endif>
                                            <label class="custom-control-label" for="agree-update">@lang('main.signup.consents.data.searching.purchases.services.company')</label>
                                        </div>
                                    </div>
                                </div>

                                <div class="col-md-12 mb-3">
                                    <div class="form-group">
                                        <label>@lang('main.signup.consents.not.obbligatory.send.mail.to.revoke')</label>
                                    </div>
                                </div>

                                <div class="col-md-12">
                                    <button class="btn btn-primary btn-block">@lang('main.Create account')</button>
                                </div>
                                <div class="mx-auto">
                                    <p class="mb-0 mt-3"><small class="text-dark mr-2">@lang('main.Already have an account') ?</small> <a href="{{ route('user.auth.showLogin') }}" class="text-dark font-weight-bold">@lang('main.Login')</a></p>
                                </div>
                            </div>
                        </form>
                        <!--./ Sign Up Form -->
                    </div>
                </div>
            </div>
            <!--end col-->
        </div>
        <!--end row-->
    </div>
    <!--end container-->
</section>
<!--end section-->
@endsection

@section('scripts')
    @include('user.include-plugins.input-tel.input-tel-js')
@endsection