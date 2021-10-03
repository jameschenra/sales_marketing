@php
    use App\Models\Service;
    use App\Models\ServiceOffice;
@endphp

{{-- Extends layout --}}
@extends('user.layout.default')

@section('meta-seo')
    <meta property="og:type" content="website"/>
    <meta name="twitter:card" content="summary"/>
    <meta name="twitter:site" content="@weredyofficial"/>
    <meta property="og:url" content="{{ URL::current() }}"/>
    <meta property="og:title" content="{{ $service->name }}"/>
    <meta property="og:description" content="{{ $service->description }}"/>
    <meta property="og:image" content="{{ HTTP_SERVICE_PATH . $service->photo }}"/>
    <meta property="fb:app_id" content="{{ env('FACEBOOK_APP_ID') }}"/>
@stop

@section('styles')
    {{ Html::style(userAsset('pages/service/booking.css')) }}
    {{ Html::style(userAsset('pages/service/step-wizard.css')) }}
@endsection

@section('content')
<section class="detail_part bg-content d-flex align-items-center">
    <div class="container">
        {{-- top image and detail part --}}
        <div class="row">
            {{-- service image --}}
            <div class="col-md-6 service-detail-img">
                <img class="img-fluid border-round" src="{{ HTTP_SERVICE_PATH . $service->photo }}" alt="service image" style="width: 100%">
            </div>
            {{--./ service image --}}

            {{-- service description --}}
            @if ($is_mobile)
                <div class="desktop-hide col-md-12 mt-4 mb-4">
                    @include('user.pages.service.booking-service-description')
                </div>
            @endif
            {{--./ service description --}}

            {{-- service detail --}}
            <div class="col-md-6 d-flex flex-column service-detail-info">
                {{-- price part --}}
                <div class="order-sm-0 order-3 text-sm-left text-center">
                    @if ($service->price == 0)
                        <h3 class="font-weight-bolder">@lang('main.Free Service')</h3>
                    @else
                        @if ($is_online)
                            <h3 class="font-weight-bolder">
                                <span class="{{ ($service->discount_percentage_single) ? 'text-decoration-line-through' : '' }}">
                                    €{{ number_format($service->price, 2) }}
                                </span>
                            </h3>

                            @if ($service->discount_percentage_single)
                                <h3 class="font-weight-bolder">
                                    €{{ number_format($service->price - ($service->price * $service->discount_percentage_single / 100), 2) }}
                                    <span class="discount-percent">-{{ (float)$service->discount_percentage_single }}%</span>
                                </h3>
                            @endif

                            @if ($service->discount_percentage_multiple && $service->discount_percentage_multiple > 0)
                                <h3 class="font-weight-bolder">
                                    €{{ number_format($service->price - ($service->price * $service->discount_percentage_multiple / 100), 2) }} 
                                    <span class="discount-percent">-{{ (float)$service->discount_percentage_multiple }}% @lang('main.if order more services')</span>
                                </h3>
                            @endif
                        @endif

                        @if($is_offline)
                            @if ($service->client_payment_type == Service::PAYMENT_TYPE_ONLINE)
                                <h3 class="font-weight-bolder {{ $service->discount_percentage_single ? 'text-decoration-line-through' : ''}}">
                                    €{{ number_format($service->price, 2) }}
                                </h3>
                            @else
                                <h3 class="service-price-wrapper">
                                    €{{ number_format($service->price, 2) }}
                                    @if ($service->client_payment_type == Service::PAYMENT_TYPE_ONLINEONSITE)
                                        <span class="price-desc text-muted">@lang('main.if pay on-site')</span> 
                                    @endif
                                </h3>
                            @endif

                            @if ($service->discount_percentage_single)
                                <h3 class="font-weight-bolder">
                                    €{{ number_format($service->price - ($service->price * $service->discount_percentage_single / 100), 2) }}
                                    <span class="discount-percent">@lang('main.Save-money') {{ (float)$service->discount_percentage_single }}% {{ $service->client_payment_type == Service::PAYMENT_TYPE_ONLINEONSITE ? trans('main.if pay online') : '' }}</span>
                                </h3>
                            @endif

                            @if ($service->discount_percentage_multiple && $service->discount_percentage_multiple > 0)
                                <h3 class="font-weight-bolder">
                                    €{{ number_format($service->price - ($service->price * $service->discount_percentage_multiple / 100), 2) }} 
                                    <span class="discount-percent">@lang('main.Save-money') {{ (float)$service->discount_percentage_multiple }}% @lang('main.if order more services')</span>
                                </h3>
                            @endif
                        @endif
                    @endif
                </div>
                {{--./ price part --}}

                <div class="order-sm-1 order-2 text-sm-left text-center">
                    @if($is_online)
                        <div class="mb-2">
                            <span class="fas fa-hand-holding-water"></span>
                            @lang('main.Delivery in') {{ trans_choice('main.times.day', $service->online_delivery_time, ['d' => $service->online_delivery_time]) }}
                            &nbsp; <span class="fa fa-file-import"></span>
                            {{ trans_choice('main.revision', $service->online_revision, ['r' => $service->online_revision]) }} 
                            {{ trans_choice('main.revision_include', $service->online_revision) }} 
                        </div>
                    @endif

                    @if ($is_offline)
                        <div class="mb-2">
                            <span class="far fa-clock"></span>
                            <span class="text-primary">{{ $service->prettyDuration }}</span>

                            @if ($service->hasConsecutively())
                                @lang('main.Possibility to book for more time')
                            @endif
                        </div>
                    @endif
                </div>

                {{-- office info --}}
                <div class="office-info mt-4 mb-4 order-sm-2 order-1 text-sm-left text-center">
                    @if ($is_online)
                        @if ($service->onlineOffice)
                            @if ($is_online)
                                @lang('main.Service will be done from'):<br />
                            @else
                                @lang('main.available in'):<br />
                            @endif
                            <strong>{{ $service->onlineOffice->city->name ?? '' }},</strong>
                            <strong>{{ $service->onlineOffice->region->name ?? '' }},</strong>
                            <strong>{{ $service->onlineOffice->country->short_name ?? '' }}</strong>
                        @endif
                    @endif
                    
                    @if ($is_offline)
                        <div class="font-size-h4 mb-2">@lang('main.available from'):</div>

                        @foreach ($offices as $office)
                            <div class="service-available mb-2">
                                <strong>{{ $office['address'] }}</strong>

                                @if (count($offices) == 1)
                                    @if ($office['onsite_type'] == ServiceOffice::TYPE_ONSITE)
                                        <div class="text-muted"><small>@lang('main.Available only on-site')</small></div>
                                    @elseif ($office['provide_range'])
                                        <div class="text-muted">
                                            @if ($office['onsite_type'] == ServiceOffice::TYPE_OFFSITE)
                                                <small>@lang('main.Available only off-site within a range of') {{ $office['provide_range'] }}km</small>
                                            @else
                                                <small>@lang('main.Available on-site and off-site within a range of') {{ $office['provide_range'] }}km</small>
                                            @endif
                                        </div>

                                        @if ($service->extra_price_type == Service::EXTRA_PRICE_FIX)
                                            <div class="mb-2 text-muted">
                                                <small>@lang("main.An extra of ") €{{ $service->extra_price }} @lang("main.will be applied to reach the address")</small>
                                            </div>
                                        @elseif ($service->extra_price_type == Service::EXTRA_PRICE_KILOMETER)
                                            <div class="mb-2 text-muted">
                                                <small>@lang("main.An extra of ") €{{ $service->extra_price }} @lang("main.will be applied for each kilometer traveled")</small>
                                            </div>
                                        @endif
                                    @endif
                                @endif
                            </div>
                        @endforeach
                    @endif
                </div>
                {{-- ./office info --}}

                {{-- seller info --}}
                <div class="d-flex order-sm-3 order-0">
                    <div class="seller-img-wrapper mr-4">
                        <a href="{{ route('user.professionals.detail', $owner->slug) }}">
                            <div class="symbol symbol-50 symbol-lg-120">
                                <img class="border-round" src="{{ HTTP_USER_PATH . $owner->detail->photo }}" alt="{{ $owner->name }}" />
                            </div>
                        </a>
                    </div>

                    <div>
                        <div class="text-dark-65">@lang('main.Provided-by')</div>
                        <div>
                            <a href="{{ route('user.professionals.detail', $owner->slug) }}"
                                    class="h3 font-weight-bolder text-dark">
                                {{ $owner->initial_name }}
                            </a>
                        </div>
                        <div>
                            @include('user.components.star-rate', ['score' => $owner->review_score])
                            <span>({{ $owner->getReviewCount() . ' ' . trans('main.Reviews') }})</span>
                        </div>
                    </div>
                </div>
                {{-- ./seller info --}}
            </div>
            {{--./ service detail --}}
        </div>
        {{--./ top image and detail part --}}
        @if ($is_mobile)
            <div id="map-canvas" style="height: 330px; width: 100%; margin-top:0px;" class="mt-4 desktop-hide"></div>
        @endif
        <br />

        {{-- service description --}}
        @if (!$is_mobile)
            <div class="mobile-hide">
                @include('user.pages.service.booking-service-description')
            </div>
        @endif
        {{--./ service description --}}

        @include('user.pages.service.booking-process')

        @include('user.pages.auth.login-popup')

        <form action="{{ route('user.booking.purchase') }}"
                id="payment-form" method="post" class="d-none" enctype="multipart/form-data">
            @csrf
            <input type="hidden" name="data">
            <div id="online-file-container"></div>
        </form>

        <form action="{{ route('user.booking.by-office') }}"
                id="office-payment-form" method="post" class="d-none">
            @csrf
            <input type="hidden" name="data">
        </form>

        <form action="{{ route('user.booking.by-free') }}"
                id="free-payment-form" method="post" class="d-none">
            @csrf
            <input type="hidden" name="data">
        </form>
    </div>
    {{-- ./container --}}
</section>

@include('user.components.share-modal')
@include('user.components.favourite-modal')
@endsection

@section('scripts')
    {{ Html::script(userAsset('common-js/date_convert.js')) }}
    @include('user.pages.service.booking-js')
    @include('user.include-js.favourite-js')
    @include('user.include-js.share-js')
@endsection
