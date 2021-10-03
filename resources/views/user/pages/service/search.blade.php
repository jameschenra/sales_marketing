@php
    $selectedPriceRange = request('price_range');
    $selectedServiceRating = request('service_rating');
    $selectedAvailability = request('availability');
    $selectedPaymentType = request('payment_type');
    
    $availabilities = [
        0 => trans('main.searchForm.any.availability'),
        1 => trans('main.searchForm.available.on-site'),
        2 => trans('main.searchForm.available.off-site'),
        3 => trans('main.searchForm.available.online'),
    ];

    $paymentTypes = [
        0 => trans('main.searchForm.any.payment'),
        1 => trans('main.searchForm.pay.online'),
        2 => trans('main.searchForm.pay.on-site')
    ];
@endphp

{{-- Extends layout --}}
@extends('user.layout.default')

@section('styles')
    {{ Html::style(userAsset('pages/profile/detail.css')) }}
    <style>
        .faq-content .card a.faq[data-toggle=collapse].collapsed:before {
            top: 8px;
        }
        .faq-content .card a.faq[data-toggle=collapse]:before {
            top: 22px;
        }
    </style>
@endsection

@section('content')
<section class="bg-content d-flex align-items-center">
    <div class="container search-container">
        <h2 class="title text-center">{{ trans('main.Services Directory') }}</h2><br /><br />

        <div class="d-flex flex-md-row flex-column">          
            {{-- Search form --}}
            <form method="get" class="mb-8">
                <div class="flex-row-lg-auto w-100 w-sm-300px w-xl-350px" id="service-search-form">
                    {{-- card begin --}}
                    <div class="card card-custom card-stretch">
                        <div class="card-body pr-0">
                            <div class="faq-content">
                                <div class="card border-0">
                                    @if ($is_mobile)
                                        <a data-toggle="collapse" href="#collapse1" class="faq position-relative collapsed" aria-expanded="false">
                                            <div class="card-header border-0 p-3 pr-5">
                                                <h4 class="mb-8">@lang('main.Filter results')</h4>
                                            </div>
                                        </a>
                                    @else
                                        <h4 class="mb-8">@lang('main.Filter results')</h4>
                                    @endif
                                    
                                    <div id="collapse1" class="{{ $is_mobile ? 'collapse' : ''}}">
                                        <div class="card-body px-2">
                                            {{-- service category filter --}}
                                            <div class="form-group">
                                                <select name="category_name" class="form-control">
                                                    <option value="">@lang('main.searchForm.selectServiceCategory')</option>
                                                    @foreach($categories as $category)
                                                        @if($category->slug != 'other')
                                                            <option value="{{$category->slug}}" {{$category->slug == $selected_category_slug ? 'selected':''}}>{{ $category->name }}</option>
                                                        @endif
                                                    @endforeach
                                                </select>
                                            </div>
                                            {{-- ./service category filter --}}

                                            {{-- service sub category filter --}}
                                            <div class="form-group <?= ($selected_category_slug || isset($sub_categories)) ? '': ' d-none' ?>">
                                                @if($selected_category_slug || isset($sub_categories))
                                                    <select name="sub_category_name" class="form-control">
                                                        <option value="">{{ trans('main.searchForm.Selectspecificsector') }}</option>
                                                        @foreach($sub_categories as $subCategory)
                                                            <option value="{{ $subCategory->slug }}" {{ $subCategory->slug == $selected_sub_category_slug ? 'selected':''}}>{{ $subCategory->name }}</option>
                                                        @endforeach
                                                    </select>
                                                @endif
                                            </div>
                                            {{-- ./service sub category filter --}}

                                            {{-- price filter --}}
                                            <div class="form-group">
                                                <select class="form-control" name="price_range">
                                                    <option value="">@lang('main.searchForm.priceFilter.service')</option>
                                                    @foreach(['low', 'high', 'free'] as $priceRange)
                                                        <option value="{{ $priceRange }}" {{ $selectedPriceRange == $priceRange ? 'selected' : '' }}>@lang('main.searchForm.priceFilter.service.' . $priceRange)</option>
                                                    @endforeach
                                                </select>
                                            </div>
                                            {{-- ./price filter --}}

                                            {{-- city filter --}}
                                            <div class="form-group">
                                                <input type="text" name="city" id="city-selector" class="form-control" autocomplete="off" placeholder="@lang('main.search-box-city')" value="{{ request('city') }}">
                                            </div>
                                            {{-- ./city filter --}}

                                            {{-- avaliable offsite filter --}}
                                            <div class="form-group">
                                                <select class="form-control" name="availability">
                                                    @foreach($availabilities as $key => $availability)
                                                        <option value="{{ $key }}" {{ $selectedAvailability == $key ? 'selected' : '' }}>{{ $availability }}</option>
                                                    @endforeach
                                                </select>
                                            </div>
                                            {{--./ avaliable offsite filter --}}

                                            {{-- payment type filter --}}
                                            <div class="form-group">
                                                <select class="form-control" name="payment_type">
                                                    @foreach($paymentTypes as $key => $paymentType)
                                                        <option value="{{ $key }}" {{ $selectedPaymentType == $key ? 'selected' : '' }}>{{ $paymentType }}</option>
                                                    @endforeach
                                                </select>
                                            </div>
                                            {{--./ payment type filter --}}

                                            {{-- rating filter --}}
                                            <div class="form-group">
                                                <select class="form-control" name="service_rating">
                                                    <option value="">@lang('main.searchForm.ratingFilter')</option>
                                                    @foreach(['low', 'high'] as $ratingRange)
                                                        <option value="{{ $ratingRange }}" {{ $selectedServiceRating == $ratingRange ? 'selected' : '' }}>@lang('main.searchForm.ratingFilter.' . $ratingRange)</option>
                                                    @endforeach
                                                </select>
                                            </div>
                                            {{-- ./rating filter --}}

                                            <div class="form-group">
                                                <div class="input-icon input-icon-right">
                                                    <input type="text" name="keyword" class="form-control"
                                                        onchange="formSubmit()"
                                                        value="{{ request('keyword') }}"
                                                        placeholder="@lang('main.searchFilter.serviceName')"/>
                                                </div>
                                            </div>

                                            <div class="text-center">
                                                <button type="button" class="btn btn-primary btn-responsive search-submit d-none">@lang('main.Search')</button>
                                                <a href="{{ route('user.services.search') }}" class="btn btn-primary btn-responsive">@lang('main.search.reset.filters')</a>
                                            </div>
                                            
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        {{-- ./card body --}}
                    </div>
                    {{-- ./card begin --}}
                </div>
            </form>
            {{-- ./Search form --}}
            
            <div class="flex-fill ml-lg-8">
                <div class="card card-custom card-stretch">
                    <div class="card-body">
                        @if(count($services) > 0)
                            @foreach ($services as $service)
                                @include('user.pages.service.detail-service-row')
                            @endforeach
                            <div class="float-right">{{ $services->appends(request()->input())->links() }}</div>
                        @else
                            <h5 class="text-center">@lang('main.ProSearch No Result')</h5>
                            <br />
                            <div class="text-center">
                                <a href="{{ route('user.professionals.search') }}" class="btn btn-primary">@lang('main.Professionals Directory')</a>
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

@include('user.components.favourite-modal')
@endsection

@section('scripts')
    <script>
        var searchBaseUrl = "{{ URL::route('user.services.search') }}" + '/';
        var mapAPIKey = "{{ env('MAP_API') }}";
    </script>
    <script src="https://maps.googleapis.com/maps/api/js?key={{ env('MAP_API') }}&libraries=places"></script>
    {{ Html::script(userAsset('common-js/address_autocomplete.js')) }}
    {{ Html::script(userAsset('pages/service/search.js')) }}

    {{ Html::script(userAsset('libraries/readmore.js')) }}
    @include('user.include-js.favourite-js')
    @include('user.include-js.readmore-js')
@endsection
