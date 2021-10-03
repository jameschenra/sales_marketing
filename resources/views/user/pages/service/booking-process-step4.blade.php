<div class="step-select-upload step-form" style="display: none">
    @if ($is_offline)
        <div class="booking-section booking-section__offsite" style="display: none">
            <h3 class="booking-section__title">
                {{ trans('main.Enter the address to book off-site') }}
            </h3>
            <div class="booking-section__offsite-place form-group">
                <input class="form-control" type="text"
                    placeholder="{{ trans('main.To book offsite type your address here') }}"
                    id="user_addr_input" name="user_addr" style="display:none" required>
                <span class="js-validation-error invalid-feedback" role="alert">@lang('main.Type address message pop-up booking')</span>
            </div>
        </div>
    @endif

    <div class="booking-section booking-section__message" style="display: none">
        <h3 class="booking-section__title">
            @if ($is_online)
                {{ trans('main.Do you want to send additional message online') }}
            @else
                {{ trans('main.Do you want to send additional message offline') }}
            @endif
        </h3>
        <div class="form-group booking-section__message">
            <textarea rows="5"
                placeholder="{{ $is_online ? trans('main.main.placeholder message online service') : trans('main.placeholder message online service') }}"
                name="user_msg" class="form-control"
                maxlength="250"
                style="margin-top:10px;height:137px !important;"></textarea>
            <span class="js-validation-error invalid-feedback d-none" role="alert">@lang('main.Enter Description')</span>
        </div>
    </div>

    @if ($is_online)
        <br />
        <div class="booking-section booking-section__file" style="display: none">
            <h3 class="booking-section__title">
                {{ trans('main.Add an attachment here') }}
            </h3>
            <div class="form-group booking-section__message">
                <input type="file" name="online_file" id="online_file" />
                <span class="js-validation-error invalid-feedback d-none" role="alert">@lang('main.Enter file')</span>
            </div>
        </div>
    @endif
</div>