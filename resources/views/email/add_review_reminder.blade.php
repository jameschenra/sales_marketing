@extends('email.layout-en')

@section('email-content')

{{ \App::setLocale($locale) }}
<div>
    <div>
        @lang('email.Review reminder email', ['buyer_name' => $buyer->name, 'seller_name' => $seller->name, 'seller_surname' => $seller->surname])
    </div>
    <br><br>
    <div class="text-align: center">
        <a href="{{ route('user.review.create', $token) }}"><button class="view-btn btn-center">@lang('email.Review reminder button')</button></a>
    </div>
    <br><br>
</div>
@stop