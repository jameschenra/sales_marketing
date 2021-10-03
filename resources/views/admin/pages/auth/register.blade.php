{{-- Extends layout --}}
@extends('admin.layout.auth')

{{-- Content --}}
@section('content')

    <!-- Hero Start -->
    <section class="bg-home d-flex align-items-center h-100">
        <div class="container">
            <div class="row justify-content-md-center">
                <div class="col-md-6">
                    <div class="card login-page bg-white shadow rounded border-0">
                        <div class="card-body">
                            <h4 class="card-title text-center">Register</h4>  
                            <form method="POST" action="{{ route('admin.auth.signup') }}" class="login-form mt-4">
                                @csrf

                                <div class="row">

                                    <div class="col-lg-12">
                                        <div class="form-group position-relative">
                                            <label>Name <span class="text-danger">*</span></label>
                                            <i data-feather="user" class="fea icon-sm icons"></i>
                                            <input type="name" class="form-control pl-5 @error('name') is-invalid @enderror"
                                                value="{{ old('name') }}" placeholder="Name" name="name" required="" />
                                            
                                            @error('name')
                                                <span class="invalid-feedback" role="alert">
                                                    <strong>{{ $message }}</strong>
                                                </span>
                                            @enderror
                                        </div>
                                    </div>

                                    <div class="col-lg-12">
                                        <div class="form-group position-relative">
                                            <label>Your Email <span class="text-danger">*</span></label>
                                            <i data-feather="user" class="fea icon-sm icons"></i>
                                            <input type="email" class="form-control pl-5 @error('email') is-invalid @enderror"
                                                value="{{ old('email') }}" placeholder="Email" name="email" required="" />
                                            
                                            @error('email')
                                                <span class="invalid-feedback" role="alert">
                                                    <strong>{{ $message }}</strong>
                                                </span>
                                            @enderror
                                        </div>
                                    </div>

                                    <div class="col-lg-12">
                                        <div class="form-group position-relative">
                                            <label>Password <span class="text-danger">*</span></label>
                                            <i data-feather="key" class="fea icon-sm icons"></i>
                                            <input type="password" class="form-control pl-5 @error('password') is-invalid @enderror"
                                                name="password" placeholder="Password" required="">
                                        </div>
                                    </div>

                                    <div class="col-lg-12 mb-0">
                                        <button class="btn btn-primary btn-block">Register</button>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div><!---->
                </div> <!--end col-->
            </div><!--end row-->
        </div> <!--end container-->
    </section><!--end section-->
    <!-- Hero End -->

@endsection

{{-- Scripts Section --}}
@section('scripts')
    
@endsection
