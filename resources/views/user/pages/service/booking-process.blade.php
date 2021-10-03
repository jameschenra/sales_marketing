@php
    use App\Models\ServiceOffice;    
    use App\Models\Service;
@endphp

<h3 class="text-center mt-16 mb-4">
    @if ($is_online)
        @lang('main.Order Information')
    @else
        @lang('main.Booking Information')
    @endif
</h3>

<div class="text-center d-inline-block d-sm-flex justify-content-center mb-10">
    @include('user.pages.service.detail-service-row-range')
    <div class="mr-4"></div>
    @include('user.pages.service.detail-service-row-status')
</div>


<div class="booking-step-wizard" id="booking-step-wizard">
    <ul class="multistep-progressbar">
        @if(!$is_online && $offices_count > 1)
            <li class="step-container current-step" data-step-form="office">
                <span class="step-icon"><i class="fa fa-check"></i></span>
                <span class="step-title">@lang('main.Select office')</span>
            </li>
        @endif

        <li class="step-container step-wizard-select-date" data-step-form="date">
            <span class="step-icon"><i class="fa fa-check"></i></span>
            <span class="step-title">@lang('main.Select Date')</span>
        </li>

        @if ($is_offline || $service->online_book_count > 1)
            <li class="step-container" data-step-form="time">
                <span class="step-icon"><i class="fa fa-check"></i></span>
                @if ($is_offline)
                    <span class="step-title">@lang('main.Select Time')</span>
                @else
                    <span class="step-title">@lang('main.Select services')</span>
                @endif
            </li>
        @endif

        <li class="step-container" data-step-form="upload">
            <span class="step-icon"><i class="fa fa-check"></i></span>
            <span class="step-title">@lang('main.Upload file or message')</span>
        </li>
        <li class="step-container" data-step-form="payment">
            <span class="step-icon"><i class="fa fa-check"></i></span>
            <span class="step-title">@lang('main.Confirm Payment')</span>
        </li>
    </ul>
</div>

<div class="booking-step-content">
    <div class="row">
        <div class="col-md-7">
            <div class="card card-custom card-stretch">
                <div class="card-body">
                    <div class="mb-10" id="booking-form-container">
                        @if ($is_offline && $offices_count > 1)
                            @include('user.pages.service.booking-process-step1')
                        @endif

                        {{-- Select Date from datepicker --}}
                        @include('user.pages.service.booking-process-step2')
                        
                        {{-- Select time --}}
                        @if ($is_offline || $service->online_book_count > 1)
                            @include('user.pages.service.booking-process-step3')
                        @endif

                        {{-- Upload file or message --}}
                        @include('user.pages.service.booking-process-step4')

                        {{-- Confirm Payment --}}
                        @include('user.pages.service.booking-process-step5')
                    </div>
                </div>

                <div class="card-footer pt-5 pb-4">
                    <button type="btn" class="btn btn-light-primary btn-booking-previous" style="display: none">@lang('main.Previous')</button>
                    <button type="btn" class="btn btn-primary btn-booking-next float-right">@lang('main.Next')</button>
                </div>
            </div>
        </div>
    
        {{-- booking detail --}}
        <div class="col-md-5">
            <div class="card card-custom">
                <div class="card-body">
                    <div class="booking-detail">
                        <div class="booking-section booking-section__details-container" data-margin-top="70">
                            <div>
                                <h3 class="booking-section__title">
                                    @if ($is_online)
                                        @lang('main.Your order details')
                                    @else
                                        @lang('main.Your Booking')
                                    @endif
                                </h3>
            
                                {{-- booking selected information --}}
                                <div class="booking-section__detail">
                                    <div class="detail-date">
                                        @if ($is_online)
                                            <p class="detail-info-title">@lang('main.Your Order date'):</p>
                                        @else
                                            <p class="detail-info-title">@lang('main.Your Booking date'):</p>
                                        @endif
                                        <div class="value"></div>
                                    </div>
            
                                    @if ($is_online)
                                        <div class="detail-delivery-date">
                                            <p class="detail-info-title">@lang('main.Your Delivery date'):</p>
                                            <div class="time-text-tab value"></div>
                                        </div>
            
                                        <div class="detail-number-of-services">
                                            <p class="d-inline-block">
                                                @lang('main.Numbers-of-services-to-order'):
                                            </p>
                                            <div class="value"></div>
                                        </div>

                                        <div class="detail-revisions">
                                            <p class="d-inline-block">
                                                @lang('main.Numbers-of-revisions-included'):
                                            </p>
                                            <div class="value">{{ $service->online_revision == -1 ? trans('main.Unlimited revisions') : $service->online_revision }}</div>
                                        </div>
            
                                        <div class="detail-working-days">
                                            <p class="d-inline-block">@lang('main.Working days'):</p>
                                            <div class="value">{{ $service->online_delivery_time }}</div>
                                        </div>
            
                                        <div class="detail-place">
                                            <p class="detail-info-title">@lang('main.Place from where the service will be provided'):</p>
                                            <div class="value">
                                                {{ $service->onlineOffice->city->name }}
                                            </div>
                                        </div>
                                    @else
                                        <div class="detail-time">
                                            <p class="detail-info-title">@lang('main.Your Booking time'):</p>
                                            <div class="time-text-tab value"></div>
                                        </div>
                                        <div class="detail-number-of-services">
                                            <p class="d-inline-block">@lang('main.Numbers-of-services-to-book'):</p>
                                            <div class="value"></div>
                                        </div>
                                        <div class="detail-duration">
                                            <p class="detail-info-title">@lang('main.Duration'):</p>
                                            <div class="value"></div>
                                        </div>
                                        <div class="detail-place">
                                            <p class="detail-info-title">@lang('main.Place where the service will be provided'):</p>
                                            <div class="value">
                                                @if ($offices_count == 1)
                                                    {{ $offices[0]['city'] }}
                                                @endif
                                            </div>
                                        </div>
                                        
                                        <div class="detail-extra-price" style="display: none">
                                            @if ($offices_count == 1)
                                                @if ($offices[0]['onsite_type'] != ServiceOffice::TYPE_ONSITE
                                                    && $offices[0]['provide_range'])
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

                                        <div class="detail-message">
                                            <p class="detail-message-header"></p><br>
                                            <div class="value"></div>
                                        </div>
                                    @endif
                                </div>
                                {{-- ./booking selected information --}}
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        {{--./ booking detail --}}
    </div>
</div>