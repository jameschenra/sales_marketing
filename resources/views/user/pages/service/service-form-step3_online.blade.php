<div class="paid-service-option-container">
    {{-- provide with file --}}
    <div class="form-group step2-options-container">
        <div class="label-with-desc">
            <label>@lang("main.Do you need files to start working?") <span class="text-danger">*</span></label>
            <div class="desc-content">@lang("main.Customer will provide you all details, in case are not enough, once ordered you can request more.")</div>
        </div>
        
        <div class="radio-inline online_file_required">
            <label class="radio mr-8">
                <input type="radio" name="online_file_required" value="1" {{ (old('online_file_required', $service->online_file_required ?? null) == 1) ? 'checked' : ''}} />
                <span></span>@lang("main.Yes, I need files and a detailed description")
            </label>
            <label class="radio mr-8">
                <input type="radio" name="online_file_required" value="0" {{ (old('online_file_required', $service->online_file_required ?? null) === 0) ? 'checked' : ''}} />
                <span></span>@lang("main.No, I only need a detailed description")
            </label>
        </div>

        @include('user.components.validation-error', ['field' => 'online_file_required'])
        <span class="js-validation-error invalid-feedback d-none" role="alert"><strong>@lang('main.Please select one option at least')</strong></span>
    </div>
    {{--./ provide with file --}}

    {{-- Price --}}
    <div class="row">
        <div class="col-md-6">
            <div class="form-group">
                <label>@lang('main.Enter price for single service') <span class="text-danger">*</span> €</label>
                <input type="number" name="online_price" class="input-decimal form-control form-control-lg @error('online_price') is-invalid @enderror"
                    placeholder="@lang('main.Enter price per single service')"
                    value="{{ number_format(old('online_price', $service->price ?? 0), 2) }}"
                    step="0.01" />
                
                @include('user.components.validation-error', ['field' => 'online_price'])
                <span class="js-validation-error invalid-feedback d-none" role="alert">@lang('main.Enter Price')</span>
            </div>
        </div>
    </div>
    {{--./ Price  --}}

    {{-- Discount Percentage --}}
    <div class="row">
        <div class="col-md-6">
            <div class="form-group">
                <label>@lang('main.Do you want to offer a discount percentage per single service?')</label>
                <div class="input-group">
                    <input type="number" name="online_discount_percentage_single"
                        class="form-control form-control-lg @error('online_discount_percentage_single') is-invalid @enderror"
                        placeholder="@lang('main.Enter discount percentage per single service')"
                        value="{{ old('online_discount_percentage_single', $service->discount_percentage_single) }}"
                        step="1" min="5" />
                    <div class="input-group-append"><span class="input-group-text">%</span></div>
                </div>

                @include('user.components.validation-error', ['field' => 'online_discount_percentage_single'])
                <span class="js-validation-error invalid-feedback d-none" role="alert">@lang('main.Please enter greater than 5')</span>
            </div>
        </div>
    </div>
    {{--./ Discount Percentage --}}

    {{-- Extra Discount Percentage --}}
    <div class="form-group online-extra-discount-container">
        <label>@lang('main.Do you want to offer a discount percentage if customer order more than one service?')</label>
        <div class="row">
            <div class="col-md-6">
                <div class="input-group">
                    <input type="number" name="online_discount_percentage_multiple"
                        class="form-control form-control-lg @error('online_discount_percentage_multiple') is-invalid @enderror"
                        placeholder="@lang('main.Enter discount percentage if order more than a service')"
                        value="{{ old('online_discount_percentage_multiple', $service->discount_percentage_multiple) }}"
                        step="1" />
                    <div class="input-group-append"><span class="input-group-text">%</span></div>
                </div>
            
                @include('user.components.validation-error', ['field' => 'online_discount_percentage_multiple'])
                <span class="js-validation-error invalid-feedback d-none" role="alert">@lang('main.Please enter greater than 5')</span>
            </div>
        </div>
    </div>
    {{--./ Extra Discount Percentage --}}
</div>