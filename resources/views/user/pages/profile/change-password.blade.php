{{-- Extends layout --}}
@extends('user.layout.default')

@section('styles')
@endsection

@section('content')
    <section class="bg-content d-flex align-items-center">
        <div class="container container-form">
            <form class="form" method="POST" action="{{ route('user.profile.password.change') }}">
                @csrf

                <div class="pb-5" data-wizard-type="step-content" data-wizard-state="current">
                    <h3 class="mb-10 font-weight-bold text-dark">@lang('main.Change Password')</h3>

                    @include('user.components.validation-top-error')

                    <div class="row">
                        {{-- current password --}}
                        <div class="col-md-6">
                            <div class="form-group">
                                <label>@lang('main.Current Password') <span class="text-danger">*</span></label>
                                <input type="password" name="password_current" class="form-control form-control-lg @error('password_current') is-invalid @enderror"
                                    placeholder="@lang('main.Current Password')" />
                                
                                @include('user.components.validation-error', ['field' => 'password_current'])
                            </div>
                        </div>
                        {{--./ current password  --}}
                    </div>

                    <div class="row">
                        {{-- new password --}}
                        <div class="col-md-6">
                            <div class="form-group">
                                <label>@lang('main.New Password') <span class="text-danger">*</span></label>
                                <input type="password" name="password" class="form-control form-control-lg @error('password') is-invalid @enderror"
                                    placeholder="@lang('main.New Password')" />
                                
                                @include('user.components.validation-error', ['field' => 'password'])
                            </div>
                        </div>
                        {{--./ new password  --}}

                        {{-- password confirmation --}}
                        <div class="col-md-6">
                            <div class="form-group">
                                <label>@lang('main.Retype Password') <span class="text-danger">*</span></label>
                                <input type="password" name="password_confirmation" class="form-control form-control-lg @error('password_confirmation') is-invalid @enderror"
                                    placeholder="@lang('main.Retype Password')" />
                                
                                @include('user.components.validation-error', ['field' => 'password_confirmation'])
                            </div>
                        </div>
                        {{--./ password confirmation --}}
                    </div>
                </div>

                <div class="border-top mt-5 pt-10">
                    <button type="submit" class="btn btn-primary font-weight-bold px-9 py-4 float-right">@lang('main.Save')</button>
                </div>
            </form>
        </div>
    </section>
@endsection