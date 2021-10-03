<div class="step-select-time step-form" style="display: none">
    @if ($is_offline)
        <div class="booking-section booking-section__hours" style="display: none">
            <h3 class="booking-section__title">@lang('main.Select-time')</h3>
            <div class="alert alert-danger error-time-required validation-error">@lang('main.Please select the time.')</div>

            <div class="available-hours form-group"></div>
        </div>

        <div class="booking-section booking-section__more-hours" style="display: none">
            <h3 class="booking-section__title">
                @lang('main.Do you want to book for longer time')
            </h3>
            <div class="more-hours form-group"></div>
        </div>
    @endif

    <div class="booking-section booking-section__more-service" style="display: none">
        <h3 class="booking-section__title">
            {{ trans('main.How many services you need') }}
        </h3>
        <div class="book-duration-container profile_detail" style="width: 100%;"></div>
    </div>
</div>