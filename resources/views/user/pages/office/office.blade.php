{{-- Extends layout --}}
@extends('user.layout.default')

@section('content')
    <section class="bg-content d-flex align-items-center">
        <div class="container container-form">
            @include('user.components.validation-top-error')

            @if (isset($over_limit))
                <div class="alert alert-danger" role="alert">@lang('main.Office limited')</div>
            @else
                @include('user.pages.office.office-form')
            @endif
        </div>
    </section>
@endsection