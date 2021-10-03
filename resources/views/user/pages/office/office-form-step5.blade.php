<!-- invoice header -->
<div class="form-group">
    <label>@lang('main.Company Name') <span class="text-danger">*</span></label>
    <input type="text" name="company_name" class="form-control form-control-lg @error('company_name') is-invalid @enderror"
        placeholder="@lang('main.Company Name placeholder')" value="{{ old('company_name', $billing_info->company_name ?? '') }}" />
    
    @include('user.components.validation-error', ['field' => 'company_name'])
    <span class="js-validation-error invalid-feedback d-none" role="alert"><strong>{{ trans('main.Enter company name') }}</strong></span>
</div>
<!--./ invoice hdeader -->

<!-- country -->
<div class="form-group">
    <label>@lang('main.Country') <span class="text-danger">*</span></label>
    <select id="invoice-country" name="invoice_country_id" class="form-control form-control-lg @error('invoice_country_id') is-invalid @enderror">
        <option value="">@lang('main.select country')</option>
        @foreach($countries as $country)
            <option value="{{ $country->id }}" {{ $country->id == old('invoice_country_id', $billing_info->invoice_country_id ?? '') ? 'selected':'' }}>{{ $country->short_name }}</option>
        @endforeach
    </select>

    @include('user.components.validation-error', ['field' => 'invoice_country_id'])
    <span class="js-validation-error invalid-feedback d-none" role="alert"><strong>{{ trans('main.Select Country') }}</strong></span>
</div>
<!--./ country -->

<div class="detail-container {{ old('invoice_country_id', $billing_info->invoice_country_id ?? '') ? '' : 'd-none' }}">
    <div class="row">
        <div class="col-xl-6">
            <!-- type of company -->
            <div class="form-group">
                <label>@lang('main.Type of Company') <span class="text-danger">*</span></label>
                <select id="company-type-id" name="company_type_id" class="form-control form-control-lg @error('company_type_id') is-invalid @enderror">
                    <option value="">@lang('main.Select type of Company')</option>
                    @foreach($company_types as $type)
                        <option value="{{ $type->id }}" {{$type->id == old('company_type_id', $billing_info->company_type_id ?? '') ? 'selected':''}}>{{ $type->name }}</option>
                    @endforeach
                </select>

                @include('user.components.validation-error', ['field' => 'company_type_id'])
                <span class="js-validation-error invalid-feedback d-none" role="alert"><strong>{{ trans('main.Select type of Company') }}</strong></span>
            </div>
            <!--./ type of company -->
        </div>

        <div class="vat-id-container col-xl-6">
            <!-- vat id -->
            <div class="form-group">
                <label><span class="label-text">@lang('main.VAT ID')</span> <span class="text-danger">*</span></label>
                <input type="text" name="invoice_vat_id" class="form-control form-control-lg @error('invoice_vat_id') is-invalid @enderror"
                    placeholder="@lang('main.Enter VAT ID')" value="{{ old('invoice_vat_id', $billing_info->invoice_vat_id ?? '') }}" />

                @include('user.components.validation-error', ['field' => 'invoice_vat_id'])
                <span class="js-validation-error invalid-feedback d-none" role="alert"><strong>{{ trans('main.Enter VAT ID') }}</strong></span>
            </div>
            <!--./ vat id -->
        </div>
    </div>

    <div class="unique-code-container row">
        <div class="col-xl-6">
            <!-- unique code -->
            <div class="form-group">
                <label>@lang('main.Unique code') <span class="text-danger">*</span></label>
                <input type="text" name="invoice_unique_code" class="form-control form-control-lg @error('invoice_unique_code') is-invalid @enderror"
                    placeholder="@lang('main.Unique code')" value="{{ old('invoice_unique_code', $billing_info->invoice_unique_code ?? '') }}"
                    maxlength="7"
                />

                @include('user.components.validation-error', ['field' => 'invoice_unique_code'])
                <span class="js-validation-error invalid-feedback d-none" role="alert"><strong>{{ trans('main.Enter Unique code or PEC') }}</strong></span>
            </div>
            <!--./ unique code -->
        </div>
        <div class="col-xl-6">
            <!-- pec -->
            <div class="form-group">
                <label>@lang('main.PEC') <span class="text-danger">*</span></label>
                <input type="email" name="invoice_pec" class="form-control form-control-lg @error('invoice_pec') is-invalid @enderror"
                    placeholder="@lang('main.PEC')" value="{{ old('invoice_pec', $billing_info->invoice_pec ?? '') }}" />

                @include('user.components.validation-error', ['field' => 'invoice_pec'])
                <span class="js-validation-error invalid-feedback d-none" role="alert"><strong>{{ trans('main.Enter Unique code or PEC') }}</strong></span>
            </div>
            <!--./ pec -->
        </div>
    </div>

    <div class="row">
        <div class="col-xl-6">
            <!-- city -->
            <div class="form-group">
                <label>@lang('main.city') <span class="text-danger">*</span></label>
                <input type="text" name="invoice_city" class="form-control form-control-lg @error('invoice_city') is-invalid @enderror"
                    placeholder="@lang('main.City')" value="{{ old('invoice_city', $billing_info->invoice_city ?? '') }}" />

                @include('user.components.validation-error', ['field' => 'invoice_city'])
                <span class="js-validation-error invalid-feedback d-none" role="alert"><strong>{{ trans('main.Enter City') }}</strong></span>
            </div>
            <!--./ city -->
        </div>
        <div class="col-xl-6">
            <!-- zip code -->
            <div class="form-group">
                <label>@lang('main.Zip Code') <span class="text-danger">*</span></label>
                <input type="text" name="billing_zip_code" class="form-control form-control-lg @error('billing_zip_code') is-invalid @enderror"
                    placeholder="@lang('main.Enter zip code')" value="{{ old('billing_zip_code', $billing_info->billing_zip_code ?? '') }}" />

                @include('user.components.validation-error', ['field' => 'billing_zip_code'])
                <span class="js-validation-error invalid-feedback d-none" role="alert"><strong>{{ trans('main.Enter zip code') }}</strong></span>
            </div>
            <!--./ zip code -->
        </div>
    </div>

    <!-- address -->
    <div class="form-group">
        <label>@lang('main.billingstreet') <span class="text-danger">*</span></label>
        <input type="text" name="billing_addr" class="form-control form-control-lg @error('billing_addr') is-invalid @enderror"
            placeholder="@lang('main.billingstreet')" value="{{ old('billing_addr', $billing_info->billing_addr ?? '') }}" />

        @include('user.components.validation-error', ['field' => 'billing_addr'])
        <span class="js-validation-error invalid-feedback d-none" role="alert"><strong>{{ trans('main.Enter Address') }}</strong></span>
    </div>
    <!--./ address -->
</div>