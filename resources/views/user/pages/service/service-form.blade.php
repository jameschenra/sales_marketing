@php
    if ($mode == 'Edit') {
        // saved service's office to array
        $serviceOfficeArr = array_map(function($office)
            {
                return array_only($office, ['office_id', 'book_count', 'book_consecutively', 'onsite_type', 'provide_range']);
            },
            $service->offices->toArray()
        );

        $officeInfos = old('office_info', $serviceOfficeArr);
        $categoryId = old('category_id', $service->category_id);
    } else {
        $officeInfos = old('office_info', null);
        $categoryId = old('category_id');
    }

    if ($officeInfos) {
        $officeInfos = array_filter($officeInfos, function($item){ return isset($item['office_id']); });
        $selectedOffices = array_column($officeInfos, null, 'office_id');
        $selectedOffices = array_map(function($i){unset($i['office_id']); return $i;}, $selectedOffices);
    } else {
        $selectedOffices = [];
    }

    $subCategories = [];
    $oldSubCategories = [];
    foreach ($categories as $category) {
        $subCategories[$category->id] = $category->subCategories->map(function ($subCategory) {
            return $subCategory->only(['id', 'name']);
        })->toArray();
        
        if ($category->id == $categoryId) {
            $oldSubCategories = $category->subCategories ?? [];
        }
    }

    if ($service) {
        $discountSingle = $service->discount_percentage_single;
        $discountMultiple = $service->discount_percentage_multiple;
    } else {
        $discountSingle = null;
        $discountMultiple = null;
    }

    $user = auth()->user();
    $default_lang = $user->default_language ?: app()->getLocale();
    $support_locales = \LaravelLocalization::getSupportedLocales();
@endphp

{{-- Extends layout --}}
@extends('user.layout.default')

@section('styles')
    {{ Html::style(userAsset('pages/service/step-wizard.css')) }}
    {{ Html::style(userAsset('pages/service/form.css')) }}
@endsection

@section('content')
<section class="bg-content d-flex align-items-center">
    <div class="container">
        <form class="form" method="POST" action="{{ route('user.service.store') }}">
            @csrf
            @isset($service->id)
                {{ Form::hidden('service_id', $service->id) }}
            @endisset
        
            <div class="pb-5">
                <div class="row">
                    <div class="col-md-3">
                        @if ($mode == 'edit')
                            <h3 class="mb-10 font-weight-bold text-dark">@lang('main.Edit Service')</h3>
                        @else
                            <h3 class="mb-10 font-weight-bold text-dark">@lang('main.Create Service')</h3>
                        @endif

                        <div class="bs-vertical-wizard">
                            <ul>
                                <li class="current">
                                    <a class="wizard-step-link" href="javascript:void(0)" data-step="1">@lang('main.Step 1') <i class="ico fa fa-check ico-white" style="display: none"></i>
                                        <span class="wizard-step-number">1</span>
                                        <span class="desc">@lang('main.Enter Service name, category and a detailed description')</span>
                                    </a>
                                </li>
    
                                <li class="">
                                    <a class="wizard-step-link" href="javascript:void(0)" data-step="2">@lang('main.Step 2') <i class="ico fa fa-check ico-white" style="display: none"></i>
                                        <span class="wizard-step-number">2</span>
                                        <span class="desc">@lang('main.Select offices, duration or delivery time')</span><br />
                                    </a>
                                </li>
                                <li class="">
                                    <a class="wizard-step-link" href="javascript:void(0)" data-step="3">@lang('main.Step 3') <i class="ico fa fa-check ico-white" style="display: none"></i>
                                        <span class="wizard-step-number">3</span>
                                        <span class="desc">@lang('main.Prices, discounts and payment method')</span><br />
                                    </a>
                                </li>
                                <li>
                                    <a class="wizard-step-link" href="javascript:void(0)" data-step="4">@lang('main.Step 4') <i class="ico fa fa-check ico-white" style="display: none"></i>
                                        <span class="wizard-step-number">4</span>
                                        <span class="desc">@lang('main.Video-call settings')</span><br />
                                    </a>
                                </li>
                                <li>
                                    <a class="wizard-step-link" href="javascript:void(0)" data-step="5">@lang('main.Step 5') <i class="ico fa fa-check ico-white" style="display: none"></i>
                                        <span class="wizard-step-number">5</span>
                                        <span class="desc">@lang('main.Additional image')</span><br />
                                    </a>
                                </li>
                            </ul>
                        </div>

                        <div class="bs-horizontal-wizard">
                            <ul class="multistep-progressbar">                        
                                <li class="step-container current-step" data-step-form="name">
                                    <span class="step-icon"><i class="fa fa-check"></i></span>
                                    <span class="step-title">Step 1</span>
                                </li>
                                <li class="step-container" data-step-form="office">
                                    <span class="step-icon"><i class="fa fa-check"></i></span>
                                    <span class="step-title">Step 2</span>
                                </li>
                                <li class="step-container" data-step-form="price">
                                    <span class="step-icon"><i class="fa fa-check"></i></span>
                                    <span class="step-title">Step 3</span>
                                </li>
                                <li class="step-container" data-step-form="video">
                                    <span class="step-icon"><i class="fa fa-check"></i></span>
                                    <span class="step-title">Step 4</span>
                                </li>
                                <li class="step-container" data-step-form="image">
                                    <span class="step-icon"><i class="fa fa-check"></i></span>
                                    <span class="step-title">Step 5</span>
                                </li>
                            </ul>
                        </div>
                    </div>
                    
                    <div class="col-md-9 pl-10 pt-5">
                        @include('user.components.validation-top-error')

                        <div id="service-form-step1" class="service-form-step-container">
                            @include('user.pages.service.service-form-step1')
                        </div>

                        <div id="service-form-step2" class="service-form-step-container">
                            @include('user.pages.service.service-form-step2')
                        </div>

                        <div id="service-form-step3" class="service-form-step-container">
                            @include('user.pages.service.service-form-step3')
                        </div>

                        <div id="service-form-step4" class="service-form-step-container">
                            @include('user.pages.service.service-form-step4')
                        </div>

                        <div id="service-form-step5" class="service-form-step-container">
                            @include('user.pages.service.service-form-step5')
                        </div>

                        <!--begin::Wizard Actions-->
                        <div class="main-step-navigation-container">
                            <div class="row pt-5">
                                <div class="col-6">
                                    @if(session()->get('from_wizard'))
                                        <a href="{{ route('user.profile.wizard', 'contact') }}" id="go-complete-profile" class="btn btn-light-primary font-weight-bold px-9 py-2">@lang('main.Previous')</a>
                                    @endif
                                    <button type="button" id="btn-previous" class="btn btn-light-primary font-weight-bold px-9 py-2 mb-4" onclick="onPreviousNavigateStep()" style="display: none">@lang('main.Previous')</button>
                                </div>
                                
                                <div class="col-6 text-right">
                                    @if(session()->get('from_wizard'))
                                        <a href="/" id="btn-post-later" class="next-button btn btn-light-primary font-weight-bold px-9 py-2">@lang('main.I will post later')</a>
                                    @endif
                                    <button type="button" class="next-button btn btn-primary font-weight-bold px-9 py-2 mb-4 mb-sm-0" onclick="onNextNavigateStep()">@lang('main.Next')</button>
                                    <div class="submit-container" style="display: none">
                                        <button type="submit" name="save_draft" value="Save to Draft" class="btn btn-secondary font-weight-bold px-9 py-2 mr-2 mb-4 mb-sm-2">
                                            @lang('main.Save Draft')</button>
                                        <button type="submit" class="btn btn-primary font-weight-bold px-9 py-2 mr-2 mb-sm-2">
                                            @lang('main.Publish')</button>
                                    </div>
                                </div>
                                
                            </div>
                        </div>

                        <div class="step2-navigation-container" style="display: none">
                            <div class="d-flex justify-content-between pt-5 text-right">
                                <button type="button" id="btn-office-previous" class="btn btn-light-primary font-weight-bold px-9 py-2" onclick="onPreviousOfficeStep()">@lang('main.Previous')</button>

                                <button type="button" class="next-button btn btn-primary font-weight-bold px-9 py-2" onclick="onNextOfficeStep()">@lang('main.Next')</button>
                            </div>
                        </div>
                        <!--end::Wizard Actions-->
                    </div>
                </div>
            </div>
        </form>
    </div>
</section>
@endsection

@section('partial-scripts')
    @include('user.pages.service.service-js')
@endsection