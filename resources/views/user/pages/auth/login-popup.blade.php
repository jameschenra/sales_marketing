<div class="modal fade" id="login-modal" tabindex="-1" role="dialog" aria-labelledby="login-modal" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title text-center">{!! __('main.Login title') !!}</h4>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <i aria-hidden="true" class="ki ki-close"></i>
                </button>
            </div>

            <div class="modal-body">
                <form method="POST" action="{{ route('user.auth.login') }}" class="login-form">
                    @csrf

                    <div class="row">
                        <div class="col-lg-12">
                            <div class="form-group position-relative">
                                <label>@lang('main.Email') <span class="text-danger">*</span></label>

                                <i data-feather="user" class="fea icon-sm icons"></i>
                                <input type="email" name="email" class="form-control pre-icon @error('email') is-invalid @enderror"
                                    value="{{ old('email') }}" placeholder="@lang('main.Email')" required autofocus>

                                @include('user.components.validation-error', ['field' => 'email'])
                            </div>
                        </div>

                        <div class="col-lg-12">
                            <div class="form-group position-relative">
                                <label>@lang('main.Password') <span class="text-danger">*</span></label>
                                <i data-feather="key" class="fea icon-sm icons"></i>

                                <input type="password" name="password" class="form-control pre-icon @error('password') is-invalid @enderror"
                                    placeholder="@lang('main.Password')" required autocomplete="current-password">

                                @include('user.components.validation-error', ['field' => 'password'])
                            </div>
                        </div>

                        <div class="col-lg-12">
                            <div class="d-flex justify-content-between">
                                <div class="form-group">
                                    <div class="custom-control custom-checkbox">
                                        <input type="checkbox" id="remember" name="remember" class="custom-control-input" {{ old('remember') ? 'checked' : '' }}>
                                        <label class="custom-control-label" for="remember">@lang('main.Remember me')</label>
                                    </div>
                                </div>
                                <p class="forgot-pass mb-0"><a href="{{ route('user.forgot-password.showLinkRequestForm') }}" class="text-dark font-weight-bold">@lang('main.Forgot Password')</a></p>
                            </div>
                        </div>
                        <div class="col-lg-12 mb-0">
                            <button class="btn btn-primary btn-block">@lang('main.Login')</button>
                        </div>

                        <div class="col-12 text-center mt-4">
                            <p class="mb-0 mt-3"><small class="text-dark mr-2">@lang('main.Dont have an account yet?')</small> <a href="{{ route('user.auth.showSignup') }}" class="text-dark font-weight-bold">@lang('main.Sign Up')</a></p>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>