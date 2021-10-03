{{-- Extends layout --}}
@extends('user.layout.default')

{{-- Content --}}
@section('content')
    <section class="bg-content d-flex align-items-center">
        <div class="container">
            <div class="row justify-content-center px-8 px-lg-10">
                <div class="col-xl-12 col-xxl-8">
                    @include("user.pages.profile.profile-form")
                </div>
            </div>
        </div>
    </section>
@endsection