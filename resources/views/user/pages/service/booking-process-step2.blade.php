<div class="step-select-date step-form" style="display: none">
    <div class="booking-section booking-section__calendar" style="display: none">
        <h3 class="booking-section__title">@lang('main.Select-date')</h3>
        <div class="alert alert-danger error-date-required validation-error">@lang('main.Please select the date.')</div>

        <div>
            @foreach($offices as $office)
                <div class="date-picker date-picker-{{ $office['office_id'] }}"></div>
            @endforeach
        </div>
    </div>
</div>