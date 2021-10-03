@extends('user.layout.default')

@section('styles')
@stop

@section('content')
<section class="detail_part bg-content d-flex align-items-center">
    <div class="container">
        <div class="text-center">
            <h2 class="title">{{ trans('main.Terms & Conditions') }}</h2>
        </div>
        <br />
        
        <div>
            @foreach ($terms as $term)
                {!! $term->content !!}
            @endforeach
        </div>
    </div>
</section>
@stop
