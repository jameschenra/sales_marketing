{{-- Extends layout --}}
@extends('admin.layout.auth')

{{-- Content --}}
@section('content')

<!-- Hero Start -->
<section class="bg-home d-flex flex-column h-100">
    {{-- Language Selector --}}
    <div class="container mt-5">
        <div class="dropdown float-right">
            <div class="topbar-item" data-toggle="dropdown" data-offset="10px,0px">
                <div class="btn btn-icon btn-clean btn-dropdown btn-lg mr-1">
                    <img class="h-20px w-20px rounded-sm" src="{{ $languageList[0]['icon'] }}" alt=""/>
                </div>
            </div>

            <div class="dropdown-menu p-0 m-0 dropdown-menu-anim-up dropdown-menu-sm dropdown-menu-right">
                
                <ul class="navi navi-hover py-4">
                    {{-- Item --}}
                    <li class="navi-item">
                        <a href="{{ url('locale/' . $languageList[1]['locale']) }}" class="navi-link">
                            <span class="symbol symbol-20 mr-3">
                                <img src="{{ $languageList[1]['icon'] }}" alt=""/>
                            </span>
                            <span class="navi-text">{{ $languageList[1]['label'] }}</span>
                        </a>
                    </li>

                </ul>

            </div>
        </div>
    </div>
    {{-- Language Selector --}}

    <div class="container h-100">
        <div class="d-flex flex-fill align-items-center h-100">
            <div class="row flex-fill justify-content-md-center">
                <div class="col-md-6">
                    <div class="card login-page bg-white shadow rounded border-0">
                        <div class="card-body">
                            <h4 class="card-title text-center">@lang('main.Login')</h4>  
                            <form method="POST" action="{{ route('admin.auth.login') }}" class="login-form mt-4">
                                @csrf

                                <div class="row">
                                    <div class="col-lg-12">
                                        <div class="form-group position-relative">
                                            <label>@lang('main.Email') <span class="text-danger">*</span></label>
                                            <i data-feather="user" class="fea icon-sm icons"></i>
                                            <input type="email" class="form-control pl-5 @error('email') is-invalid @enderror"
                                                value="{{ old('email') }}" placeholder="@lang('main.Email')" name="email" required="" />
                                            
                                            @error('email')
                                                <span class="invalid-feedback" role="alert">
                                                    <strong>{{ $message }}</strong>
                                                </span>
                                            @enderror
                                        </div>
                                    </div>

                                    <div class="col-lg-12">
                                        <div class="form-group position-relative">
                                            <label>@lang('main.Password') <span class="text-danger">*</span></label>
                                            <i data-feather="key" class="fea icon-sm icons"></i>
                                            <input type="password" class="form-control pl-5 @error('password') is-invalid @enderror"
                                                name="password" placeholder="@lang('main.Password')" required="">
                                        </div>
                                    </div>

                                    <div class="col-lg-12">
                                        <div class="d-flex justify-content-between">
                                            <div class="form-group">
                                                <div class="custom-control custom-checkbox">
                                                    <input type="checkbox" class="custom-control-input" id="customCheck1">
                                                    <label class="custom-control-label" for="customCheck1">@lang('main.Remember me')</label>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-lg-12 mb-0">
                                        <button class="btn btn-primary btn-block">@lang('main.Login')</button>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div><!---->
                </div> <!--end col-->
            </div><!--end row-->
        </div>
        
    </div> <!--end container-->
</section><!--end section-->
<!-- Hero End -->

@endsection

{{-- Scripts Section --}}
@section('scripts')
@endsection
