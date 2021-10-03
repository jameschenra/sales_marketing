{{-- enable calendar --}}
<div class="form-group">
    <label>@lang("main.Will you enable calendar?") <span class="text-danger">*</span></label><br />
    
    <div class="has_calendar radio-inline">
        <label class="radio mr-8 mb-2">
            <input type="radio" name="has_calendar" value="0" {{ $hasCalendar == '0' ? 'checked' : ''}} />
            <span></span>@lang('main.No, I will not use calendar')
        </label>
        <label class="radio mr-8">
            <input type="radio" name="has_calendar" value="1" {{ $hasCalendar == '1' ? 'checked' : ''}} />
            <span></span>@lang('main.Yes, I\'ll use calendar')
        </label>
    </div>

    <span class="js-validation-error invalid-feedback d-none" role="alert"><strong>{{ trans('main.Select calendar visibility') }}</strong></span>
</div>
{{--./ enable calendar --}}

<!-- full calendar -->
<div class="form-group calendar-container" style="display: none">
    <label>@lang('main.Holidays')</label> @lang('main.Holidays description')
    <input type="hidden" id="holidays" name="holidays" value="{{ old('holidays') }}" />
    <div class="full-row" style="width: 100%; height: auto; padding: 0px;">
        <div id="full-year" class="box" style="width:100%; height: 100%"></div>
    </div>
</div>
<!--./ full calendar -->