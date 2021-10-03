@php
    use App\Models\Service;
    use App\Models\ServiceOffice;
@endphp
<div class="detail-service-row">
    <div class="row">
        {{-- row left part --}}
        <div class="col-md-9">
            <div class="detail-row-container">
                {{-- first column --}}
                <div class="d-flex flex-sm-row flex-column mb-3 text-center text-sm-left">
                    <div class="service-img-wrapper mr-3">
                        <a href="{{ route('user.service.show-booking', $service->slug) }}">
                            <img class="border-round" src="{{ HTTP_SERVICE_PATH . $service->photo }}" alt="service-logo" />
                        </a>
                    </div>

                    <div class="mt-6 mt-sm-0">
                        <h3><a href="{{ route('user.service.show-booking', $service->slug) }}">{{ $service->name }}</a></h3>
                        <div class="mb-2 service-category-container">
                            @if($service->subCategory)
                                <a href="{{ route('user.services.search', ['category_name' =>$service->category->slug]) }}">{{ $service->category->name }}</a> / 
                                <a href="{{ route('user.services.search', ['category_name' => $service->category->slug, 'sub_category_name' => $service->subCategory->slug]) }}">
                                    {{ $service->subCategory->name }}
                                </a>
                            @endif
                        </div>

                        <div class="d-none d-sm-block">
                            @include('user.pages.service.detail-service-row-range')
                            @include('user.pages.service.detail-service-row-status')
                        </div>
                    </div>

                    {{-- favourite icon --}}
                     @auth
                        <div class="share-icon-wrapper mr-4 ml-auto">
                            @if($service->getFavouriteId())
                                <a href="javascript:void(0)" id="fav_modal_btn_add" onclick="deleteFavouriteModal({{ $service->getFavouriteId() }}, '{{ \App\Models\Favourite::TYPE_SERVICE }}')">
                                    <span class="fas fa-heart icon-lg text-warning"></span>
                                </a>
                            @else
                                <a href="javascript:void(0)" id="fav_modal_btn_add" onclick="openServiceFavModal(event, '{{ $service->id }}', '{{ $service->name }}')">
                                    <span class="far fa-heart icon-lg text-dark"></span>
                                </a>
                            @endif
                        </div>
                    @endauth
                    {{-- ./ favourite icon --}}
                </div>
                {{-- ./first column --}}

                {{-- second column --}}
                <div class="row">
                    <div class="col-12 order-2 order-sm-1 mt-5 mt-sm-0 d-flex">
                        @php $user = $service->user; @endphp
                        <div class="mr-2">
                            <a href="{{ route('user.professionals.detail', $user->slug) }}">
                                @if($user->detail->photo)
                                    <img class="user-logo-mini border-round" src="{{ HTTP_USER_PATH . $user->detail->photo }}" alt="user photo">
                                @else
                                    <img class="user-logo-mini border-round" src="{{ HTTP_USER_PATH . DEFAULT_PHOTO }}" alt="user photo">
                                @endif
                            </a>
                        </div>
    
                        <div>
                            <div class="font-size-h6 line-height-1">@lang('main.Provided-by')</div>
                            <div class="font-size-h5 font-weight-boldest">
                                <a href="{{ route('user.professionals.detail', $user->slug) }}">{{ $user->initial_name }}</a>&nbsp;
                                @include('user.components.star-rate', ['score' => $service->review_score])
    
                                <span class="font-size-h6 font-weight-light m-l-10">({{ $service->getReviewCount() }})</span>
                            </div>
                        </div>
                    </div>

                    <div class="col-12 order-1 order-sm-2">
                        <div class="show-read-more mobile text-dark-50">{{ $service->description }}</div>
                    </div>
                </div>
                {{-- ./second column --}}
            </div>
            
        </div>
        {{-- ./row left part --}}

        {{-- row right part --}}
        <div class="col-md-3 d-flex flex-column justify-content-sm-between text-center text-sm-left">
            <div class="row mt-5 mt-sm-0">
                @if ($service->provide_online_type == Service::PROVIDE_ONLINE_TYPE)
                    <div class="col-12 order-3 order-sm-1">
                        @if($service->price > 0)
                            <div class="service-price-wrapper">
                                <span class="{{ ($service->discount_percentage_single) ? 'text-decoration-line-through' : '' }}">
                                    €{{ number_format($service->price, 2) }}
                                </span>
                            </div>
                            @if ($service->discount_percentage_single)
                                <div class="service-price-wrapper">
                                    €{{ number_format($service->price - ($service->price * $service->discount_percentage_single / 100), 2) }}
                                    <span class="discount-percent">-{{ (float)$service->discount_percentage_single }}%</span>
                                </div>
                            @endif

                            @if($service->discount_percentage_multiple && $service->discount_percentage_multiple > 0)
                                <div class="service-price-wrapper">
                                    €{{ number_format($service->price - ($service->price * $service->discount_percentage_multiple / 100), 2) }} <span class="discount-percent">-{{ (float)$service->discount_percentage_multiple }}% @lang('main.if order more services')</span>
                                </div>
                            @endif
                        @else
                            <div class="service-price-wrapper">
                                @lang('main.Free Service')
                            </div>
                        @endif
                    </div>

                    @if ($service->onlineOffice)
                        <div class="col-12 order-1 order-sm-2">
                            @lang('main.Service will be done from'):<br />
                            
                            <strong>{{ $service->onlineOffice->city->name ?? '' }},</strong>
                            <strong>{{ $service->onlineOffice->region->name ?? '' }},</strong>
                            <strong>{{ $service->onlineOffice->country['short_name_'. App::getLocale()] ?? '' }}</strong>
                        </div>
                    @endif
                @else
                    <div class="col-12 order-3 order-sm-1">
                        @if($service->price > 0)
                            @if ($service->client_payment_type == Service::PAYMENT_TYPE_ONLINE)
                                <div class="service-price-wrapper {{ ($service->discount_percentage_single) ? 'text-decoration-line-through' : '' }}">
                                    €{{ number_format($service->price, 2) }}
                                </div>
                            @else
                                <div class="service-price-wrapper">
                                    €{{ number_format($service->price, 2) }}
                                    @if ($service->client_payment_type == Service::PAYMENT_TYPE_ONLINEONSITE)
                                        <span class="price-desc text-muted">@lang('main.if pay on-site')</span> 
                                    @endif
                                </div>
                            @endif

                            @if ($service->discount_percentage_single)
                                <div class="service-price-wrapper">
                                    €{{ number_format($service->price - ($service->price * $service->discount_percentage_single / 100), 2) }} 
                                    <span class="discount-percent">@lang('main.Save-money') {{ (float)$service->discount_percentage_single }}%
                                        {{ $service->client_payment_type == Service::PAYMENT_TYPE_ONLINEONSITE ? trans('main.if pay online') : '' }}
                                    </span>
                                </div>
                            @endif

                            @if ($service->discount_percentage_multiple && $service->discount_percentage_multiple > 0)
                                <div class="service-price-wrapper">
                                    €{{ number_format($service->price - ($service->price * $service->discount_percentage_multiple / 100), 2) }} 
                                    <span class="discount-percent">@lang('main.Save-money') {{ (float)$service->discount_percentage_multiple }}%
                                        @lang('main.if order more services')
                                    </span>
                                </div>
                            @endif
                        @else
                            <div class="service-price-wrapper">
                                @lang('main.Free Service')
                            </div>
                        @endif
                    </div>

                    <div class="col-12 order-1 order-sm-2 mt-3">
                        <div class="mb-0">@lang('main.available in'):</div>
                        @foreach($service->offices as $serviceOffice)
                            @if($serviceOffice->office)
                                <div>
                                    <strong>{{ $serviceOffice->office->city->name ?? '' }},</strong>
                                    <strong>{{ $serviceOffice->office->region->name ?? '' }},</strong>
                                    <strong>{{ $serviceOffice->office->country['short_name_'. App::getLocale()] ?? '' }}</strong>
                                </div>
                            @endif
                        @endforeach
                    </div>
                @endif

                <div class="col-12 order-2 order-sm-3 d-sm-none mt-5">
                    @include('user.pages.service.detail-service-row-range')
                    @include('user.pages.service.detail-service-row-status')
                </div>
            </div>

            <a class="btn-view-service" href="{{ route('user.service.show-booking', $service->slug) }}">
                <button class="btn btn-primary btn-responsive" type="button" value="button">@lang('main.View-Service')</button>
            </a>
        </div>
        {{-- ./row right part --}}
    </div>
</div>
<br /><hr /><br />