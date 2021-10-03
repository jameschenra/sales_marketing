@php
    use App\Models\Service;
    use App\Models\ServiceOffice;    
@endphp

<div class="step-select-office step-form">
    <h3 class="booking-section__title">@lang('main.Select Office')</h3>

    <div class="alert alert-danger error-office-required validation-error">@lang('main.Please select one office.')</div>
    {{-- booking select office --}}
    @foreach ($offices as $office)
        <div class="booking-office__item" data-office-id="{{ $office['office_id'] }}">
            <div class="d-flex justify-content-between">
                <div class="h5">
                    <span class="font-weight-boldest">{{ $office['address'] }}</span>
                </div>
                @if ($service->discount_percentage_multiple
                    && $service->discount_percentage_multiple > 0
                    && ($office['book_count'] > 1 || $office['book_consecutively'] > 1)
                )
                    <span class="discount-percent">-{{ $service->discount_percentage_multiple }}% @lang('main.if order more services')</span>
                @endif
            </div>
            

            <div class="text-muted">
                @if ($office['onsite_type'] == ServiceOffice::TYPE_ONSITE)
                    <div><small>@lang('main.Available only on-site')</small></div>
                @elseif ($office['provide_range'])
                    <div>
                        @if($office['onsite_type'] == ServiceOffice::TYPE_OFFSITE)
                            <small>@lang('main.Available only off-site within a range of') {{ $office['provide_range'] }}km</small>
                        @else
                            <small>@lang('main.Available on-site and off-site within a range of') {{ $office['provide_range'] }}km</small>
                        @endif
                    </div>

                    @if ($service->extra_price_type == Service::EXTRA_PRICE_FIX)
                        <div class="mb-2">
                            <small>@lang("main.An extra of ") €{{ $service->extra_price }} @lang("main.will be applied to reach the address")</small>
                        </div>
                    @elseif ($service->extra_price_type == Service::EXTRA_PRICE_KILOMETER)
                        <div class="mb-2">
                            <small>@lang("main.An extra of ") €{{ $service->extra_price }} @lang("main.will be applied for each kilometer traveled")</small>
                        </div>
                    @endif
                @endif
            </div>

            <div class="text-right">
                <button class="btn btn-soft-light btn-select-location">@lang('main.Select location')</button>
            </div>

        </div>
    @endforeach
    {{--./ booking select office --}}
</div>