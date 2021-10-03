@extends('user.layout.default')

@section('styles')
    <style>
        b, strong {
            font-weight: 700;
        }
    </style>
@stop

@section('content')
<section class="detail_part bg-content d-flex align-items-center">
    <div class="container">
        <div class="text-center">
            <h2 class="title">{{ trans('main.Privacy Policy') }}</h2>
        </div>
        <br /><br />

        <div>
            {!! $policy->content !!}
        </div>
    </div>
</section>
@stop
