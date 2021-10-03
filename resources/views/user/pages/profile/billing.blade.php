{{-- Extends layout --}}
@extends('user.layout.default')

{{-- Content --}}
@section('content')
    <section class="bg-content d-flex align-items-center">
        <div class="container">
            <div class="row justify-content-center">
                <div class="col-xl-12 col-xxl-8">
                    @include("user.pages.profile.billing-form")
                </div>
            </div>
        </div>
    </section>
@endsection