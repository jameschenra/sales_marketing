@php
    $selectedPriceRange = request('price_range');
    $selectedServiceRating = request('service_rating');
    $selectedLanguage = request('language');
    $selectedCountry = request('country');
@endphp

{{-- Extends layout --}}
@extends('user.layout.default')

@section('styles')
    {{ Html::style(userAsset('pages/profile/detail.css')) }}
@endsection

@section('content')
<section class="bg-content d-flex align-items-center">
    <div class="container">
        <h2 class="title text-center">{{ trans('main.Professionals Directory') }}</h2>
        <br /><br />
        
        <div class="d-flex flex-md-row flex-column">
            {{-- Search form --}}
            <form method="get" class="mb-5">
                <div class="flex-row-lg-auto w-100 w-sm-300px w-xl-350px" id="service-search-form">
                    {{-- card begin --}}
                    <div class="card card-custom card-stretch">
                        <div class="card-body">
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
                                                    <option value="">@lang('main.searchForm.anycategoryofProfessions')</option>
                                                    @foreach ($profCategories as $profCategory)
                                                        @if ($profCategory->slug != 'other')
                                                            <option value="{{$profCategory->slug}}" {{$profCategory->slug == $selected_category_slug ? 'selected':''}}>{{ $profCategory->name }}</option>
                                                        @endif
                                                    @endforeach
                                                </select>
                                            </div>
                                            {{-- ./service category filter --}}

                                            {{-- service profession filter --}}
                                            @if (isset($professions))
                                            <div class="form-group <?= ($selected_category_slug || isset($professions)) ? '': ' d-none' ?>">
                                                <select name="profession_name" class="form-control">
                                                    <option value="">{{ trans('main.searchForm.selectProfession') }}</option>
                                                    @foreach ($professions as $profession)
                                                        <option value="{{ $profession->slug }}" {{ $profession->slug == $selected_profession_slug ? 'selected' : ''}}>{{ $profession->name }}</option>
                                                    @endforeach
                                                </select>
                                            </div>
                                            @endif
                                            {{-- ./service profession filter --}}

                                            {{-- price filter --}}
                                            <div class="form-group">
                                                <select class="form-control" name="price_range">
                                                    <option value="">@lang('main.searchForm.priceFilter')</option>
                                                    @foreach(['low', 'high'] as $priceRange)
                                                        <option value="{{ $priceRange }}" {{ $selectedPriceRange == $priceRange ? 'selected' : '' }}>@lang('main.searchForm.priceFilter.service.' . $priceRange)</option>
                                                    @endforeach
                                                </select>
                                            </div>
                                            {{-- ./price filter --}}

                                            {{-- language --}}
                                            <div class="form-group">
                                                <select name="language" class="form-control">
                                                    <option value="">{{ trans('main.searchForm.selectLanguage') }}</option>
                                                    @foreach($languages as $language)
                                                        <option value="{{ $language->code }}" {{ $language->code == $selectedLanguage ? 'selected' : '' }}>{{ $language->name }}</option>
                                                    @endforeach
                                                </select>
                                            </div>
                                            {{-- ./language --}}

                                            {{-- country --}}
                                            <div class="form-group">
                                                <select name="country" class="form-control">
                                                    <option value="">@lang('main.searchForm.selectCountry')</option>
                                                    @foreach($countries as $country)
                                                        <option value="{{ $country->id }}" {{ $country->id == $selectedCountry ? 'selected' : '' }}>{{ $country->short_name }}</option>
                                                    @endforeach
                                                </select>
                                            </div>
                                            {{-- ./country --}}

                                            {{-- city filter --}}
                                            <div class="form-group">
                                                <input type="text" name="city" id="city-selector" class="form-control" autocomplete="off" placeholder="@lang('main.search-box-city')" value="{{ request('city') }}">
                                            </div>
                                            {{-- ./city filter --}}

                                            {{-- country --}}
                                            <div class="form-group">
                                                <select class="form-control" name="user_rating">
                                                    <option value="">{{ trans('main.searchForm.ratingFilter') }}</option>
                                                    <?php $selectedCompanyRating = request('user_rating'); ?>
                                                    @foreach(['low', 'high'] as $ratingRange)
                                                    <option value="{{ $ratingRange }}" {{ $selectedCompanyRating == $ratingRange ? 'selected' : '' }}>{{ trans('main.searchForm.ratingFilter.' . $ratingRange) }}</option>
                                                    @endforeach
                                                </select>
                                            </div>
                                            {{-- ./country --}}

                                            {{-- avaliable offsite filter --}}
                                            <div class="form-group">
                                                <div class="custom-control custom-checkbox">
                                                    <input type="checkbox" name="online" id="online" value="1"
                                                        class="custom-control-input" @if(request('online')) checked @endif>
                                                    <label class="custom-control-label" for="online">
                                                        @lang('main.Online')<br />
                                                    </label>
                                                </div>
                                            </div>
                                            {{--./ avaliable offsite filter --}}

                                            {{-- avaliable offsite filter --}}
                                            <div class="form-group">
                                                <div class="custom-control custom-checkbox">
                                                    <input type="checkbox" name="available_office" id="available-office" value="1"
                                                        class="custom-control-input" @if(request('available_office')) checked @endif>
                                                    <label class="custom-control-label" for="available-office">
                                                        @lang('main.Offsite Available')<br />
                                                    </label>
                                                </div>
                                            </div>
                                            {{--./ avaliable offsite filter --}}

                                            <div class="form-group">
                                                <div class="input-icon input-icon-right">
                                                    <input type="text" name="keyword" class="form-control"
                                                        onchange="formSubmit()"
                                                        value="{{ request('keyword') }}"
                                                        placeholder="@lang('main.searchFilter.professionalName')"/>
                                                    {{-- <span><i class="flaticon2-search-1 icon-md"></i></span> --}}
                                                </div>
                                            </div>

                                            <div class="text-center">
                                                <button type="button" class="btn btn-primary btn-responsive search-submit d-none">@lang('main.Search')</button>
                                                <a href="{{ route('user.professionals.search') }}" class="btn btn-primary btn-responsive">@lang('main.search.reset.filters')</a>
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
                        @if(count($users) > 0)
                            @foreach ($users as $userModel)
                                @include('user.pages.professional.detail-profession-row', ['user' => $userModel])
                                <hr />
                            @endforeach
                            <br />
                            <div class="float-right">{{ $users->appends(request()->input())->links() }}</div>
                        @else
                            <h5 class="text-center">@lang('main.Service No Result')</h5>
                            <br />
                            <div class="text-center">
                                <a href="{{ route('user.services.search') }}" class="btn btn-primary">@lang('main.Services Directory')</a>
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

@include('user.components.favourite-modal')
@include('user.components.share-modal')
@endsection

@section('scripts')
    {{ Html::script(userAsset('libraries/readmore.js')) }}
    @include('user.include-js.readmore-js')

    @include('user.include-js.favourite-js')
    @include('user.include-js.share-js')
    <script>
        var searchBaseUrl = "{{ URL::route('user.professionals.search') }}" + '/';
        var mapAPIKey = "{{ env('MAP_API') }}";
    </script>
    <script src="https://maps.googleapis.com/maps/api/js?key={{ env('MAP_API') }}&libraries=places"></script>
    {{ Html::script(userAsset('common-js/address_autocomplete.js')) }}
    {{ Html::script(userAsset('pages/professional/search.js')) }}
@endsection
