@php
    $countryId = old('invoice_country_id', $billing_info->invoice_country_id ?? null);
    $companyTypeId = old('company_type_id', $billing_info->company_type_id ?? null);
@endphp

<form class="form" method="POST" action="{{ route('user.profile.billing.store') }}">
    @csrf
    <input type="hidden" name="billing_info_id" value="{{ $billingId ?? null }}" />

    <div class="pb-5" data-wizard-type="step-content" data-wizard-state="current">
        <h3 class="mb-10 font-weight-bold text-dark">@lang('main.Billing details title')</h3>
        <!-- invoice header -->
        <div class="form-group">
            <label>@lang('main.Company Name') <span class="text-danger">*</span></label>
            <input type="text" name="company_name" class="form-control form-control-lg @error('company_name') is-invalid @enderror"
                placeholder="@lang('main.Company Name placeholder')" value="{{ old('company_name', $billing_info->company_name ?? '') }}" />
            
            @include('user.components.validation-error', ['field' => 'company_name'])
        </div>
        <!--./ invoice hdeader -->

        <!-- country -->
        <div class="form-group">
            <label>@lang('main.Country')</label>
            <select id="invoice-country" name="invoice_country_id" class="form-control form-control-lg @error('invoice_country_id') is-invalid @enderror">
                <option value="">@lang('main.select country')</option>
                @foreach($countries as $c)
                    <option value="{{$c->id}}" {{$c->id == $countryId ? 'selected':''}}>{{$c->short_name}}</option>
                @endforeach
            </select>

            @include('user.components.validation-error', ['field' => 'invoice_country_id'])
        </div>
        <!--./ country -->

        <div class="detail-container {{ $countryId ? '' : 'd-none' }}">
            
            <div class="row">
                <div class="col-xl-6">
                    <!-- type of company -->
                    <div class="form-group">
                        <label>@lang('main.Type of Company') <span class="text-danger">*</span></label>
                        <select id="company-type-id" name="company_type_id" class="form-control form-control-lg @error('company_type_id') is-invalid @enderror">
                            <option value="">@lang('main.Select type of Company')</option>
                            @foreach($company_types as $type)
                                <option value="{{ $type->id }}" {{$type->id == $companyTypeId ? 'selected':''}}>{{ $type->name }}</option>
                            @endforeach
                        </select>

                        @include('user.components.validation-error', ['field' => 'company_type_id'])
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
                            maxlength="7" />

                        @include('user.components.validation-error', ['field' => 'invoice_unique_code'])
                    </div>
                    <!--./ unique code -->
                </div>
                <div class="col-xl-6">
                    <!-- address -->
                    <div class="form-group">
                        <label>@lang('main.PEC') <span class="text-danger">*</span></label>
                        <input type="email" name="invoice_pec" class="form-control form-control-lg @error('invoice_pec') is-invalid @enderror"
                            placeholder="@lang('main.PEC')" value="{{ old('invoice_pec', $billing_info->invoice_pec ?? '') }}" />

                        @include('user.components.validation-error', ['field' => 'invoice_pec'])
                    </div>
                    <!--./ address -->
                </div>
            </div>

            <div class="row">
                <div class="col-xl-6">
                    <!-- city -->
                    <div class="form-group">
                        <label>@lang('main.city') <span class="text-danger">*</span></label>
                        <input type="text" name="invoice_city" class="form-control form-control-lg @error('invoice_city') is-invalid @enderror"
                            placeholder="@lang('main.city')" value="{{ old('invoice_city', $billing_info->invoice_city ?? '') }}" />

                        @include('user.components.validation-error', ['field' => 'invoice_city'])
                    </div>
                    <!--./ city -->
                </div>
                <div class="col-xl-6">
                    <!-- zip code -->
                    <div class="form-group">
                        <label>@lang('main.Zip Code') <span class="text-danger">*</span></label>
                        <input type="text" name="billing_zip_code" class="form-control form-control-lg @error('billing_zip_code') is-invalid @enderror"
                            placeholder="@lang('main.Zip Code')" value="{{ old('billing_zip_code', $billing_info->billing_zip_code ?? '') }}" />

                        @include('user.components.validation-error', ['field' => 'billing_zip_code'])
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
            </div>
            <!--./ address -->
        </div>
    </div>

    <!--begin::Wizard Actions-->
    <div class="mt-5">
        <div class="float-right">
            <button type="submit" class="btn btn-primary font-weight-bold px-9 py-2">@lang('main.Save')</button>
        </div>
    </div>
    <!--end::Wizard Actions-->
</form>

@section('scripts')
    @include('user.pages.profile.billing-js')
@endsection