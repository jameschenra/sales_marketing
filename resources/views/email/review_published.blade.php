@extends('email.layout-en')

@section('email-content')

{{ \App::setLocale($locale) }}
<div>
    <div>
        {!! trans('email.Review published email', ['buyer_name' => $buyer->name, 'seller_name' => $seller->name, 'seller_surname' => $seller->last_name]) !!}
    </div>
    <br><br>
    <div class="text-align: center">
        <a href="{{ url('/professionals/detail/'.$seller->slug.'#tab_reviews') }}"><button class="view-btn btn-center">{{ trans('email.See review button') }}</button></a>
    </div>
    <br><br>
</div>
@stop