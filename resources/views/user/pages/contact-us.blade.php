@extends('user.layout.default')

@section('styles')
    @include('user.include-plugins.input-tel.input-tel-css')
@endsection

@section('content')
    <section class="bg-content d-flex align-items-center">
        <div class="container">
            <h2 class="text-center text-uppercase">{{ trans('main.Contact Us') }}</h2>
            <br />

            <div class="row justify-content-center">
                <div class="col-lg-6 col-md-6 mt-4 mt-sm-0 pt-2 pt-sm-0">

                    <div class="mb-10">
                        {!! trans('main.contact.description') !!}
                    </div>

                    <div class="card p-4">
                        <div class="card-body">
                            <form action="{{ route('user.contact-us.send') }}" class="form-horizontal form-row-seperated" method="post">
                                @csrf

                                {{-- name --}}
                                <div class="form-group position-relative">
                                    <label>@lang('main.Name') <span class="text-danger">*</span></label>

                                    <i data-feather="user" class="fea icon-sm icons"></i>
                                    <input type="text" name="name" id="name" class="form-control pre-icon @error('name') is-invalid @enderror"
                                        value="{{ old('name') }}" placeholder="@lang('main.Name')" autocomplete="off" autofocus required>

                                    @include('user.components.validation-error', ['field' => 'name'])
                                </div>
                                {{-- ./name --}}

                                {{-- email --}}
                                <div class="form-group position-relative">
                                    <label>@lang('main.Email') <span class="text-danger">*</span></label>

                                    <i data-feather="user" class="fea icon-sm icons"></i>
                                    <input type="email" name="email" class="form-control pre-icon @error('email') is-invalid @enderror"
                                        value="{{ old('email') }}" placeholder="@lang('main.Email')" required autofocus>

                                    @include('user.components.validation-error', ['field' => 'email'])
                                </div>
                                {{-- ./email --}}

                                <!-- phone -->
                                <div class="form-group position-relative">
                                    <label>@lang('main.Phone')</label>

                                    <input type="text" name="phone" id="phone" class="form-control @error('phone') is-invalid @enderror"
                                        value="{{ old('phone') }}" placeholder="@lang('main.Phone')" autocomplete="off">

                                    @error('phone')
                                        <span class="invalid-feedback d-block" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                    @enderror
                                </div>
                                <!--./ phone -->

                                {{-- request option --}}
                                <div class="form-group">
                                    <label>@lang('main.contact.supportRequest') <span class="text-danger">*</span></label>

                                    <select name="request_option" class="form-control @error('request_option') is-invalid @enderror">
                                        <option value="">{{ trans('main.contact.supportRequest.placeholder') }}</option>
                                        @foreach($requestOptions as $requestOption)
                                            <option value="{{ $requestOption }}" {{ old('request_option') == $requestOption ? 'selected' : '' }}>{{ trans($requestOption) }}</option>
                                        @endforeach
                                    </select>

                                    @include('user.components.validation-error', ['field' => 'request_option'])
                                </div>
                                {{-- ./request option --}}

                                {{-- description --}}
                                <div class="form-group">
                                    <label>@lang('main.contact.message') <span class="text-danger">*</span></label>

                                    <textarea rows="5" placeholder="{{ trans('main.contact.message') }}" id="message" 
                                        class="form-control @error('message') is-invalid @enderror" name="message">{{ old('message') }}</textarea>

                                    @include('user.components.validation-error', ['field' => 'message'])
                                </div>
                                {{-- ./description --}}

                                <div class="mt-10 text-right">
                                    <button type="submit" class="btn btn-primary">{{ trans('main.CustomerSupport.Send') }} <span class="glyphicon glyphicon-ok-circle"></span></button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
                {{-- ./col --}}
            </div>
            {{-- ./row --}}
        </div>
    </section>
@endsection

@section('scripts')
    @include('user.include-plugins.input-tel.input-tel-js')    
@endsection
