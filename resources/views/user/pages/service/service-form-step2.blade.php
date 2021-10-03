<div class="col-md-12">
    {{-- provide online --}}
    <div class="form-group step2-options-container">
        <div class="label-with-desc">
            <label>@lang("main.Will your service be provided online?") <span class="text-danger">*</span></label>
            <div class="desc-content">@lang("main.step-2description-in-case-online")</div>
        </div>
        
        <div class="provide_online_type radio-inline">
            <label class="radio mr-8">
                <input type="radio" name="provide_online_type" value="1" {{ (old('provide_online_type', $service->provide_online_type ?? null) == 1) ? 'checked' : ''}} />
                <span></span>@lang('main.Yes, I\'ll provide online')
            </label>
            <label class="radio mr-8">
                <input type="radio" name="provide_online_type" value="2" {{ (old('provide_online_type', $service->provide_online_type ?? null) == 2) ? 'checked' : ''}} />
                <span></span>@lang('main.No, I need to see the client')
            </label>
        </div>

        @include('user.components.validation-error', ['field' => 'provide_online_type'])
        <span class="js-validation-error invalid-feedback d-none" role="alert"><strong>@lang('main.Provide Online Type is required')</strong></span>
    </div>
    {{--./ provide online --}}

    <div class="online-option-container" style="display: none">
        @include('user.pages.service.service-form-step2_online')
    </div>
    
    <div class="offline-option-container" style="display: none">
        @include('user.pages.service.service-form-step2_offline')
    </div>

    <input type="hidden" name="is_multiple_service" value="" />
    <input type="hidden" name="is_available_offsite" value="" />
</div>