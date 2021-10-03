@php
    use App\Models\Service;
@endphp

{{-- client pay type --}}
<div class="form-group">
    <label>@lang("main.How do you want your clients pay?") <span class="text-danger">*</span></label><br />

    <div class="radio-inline client_payment_type">
        @foreach($payment_types as $paymentType)
            <label class="radio mr-8">
                <input type="radio" name="client_payment_type" value="{{ $paymentType['type_id'] }}"
                    {{ old('client_payment_type', $service->client_payment_type ?? null) == $paymentType['type_id'] ? 'checked' : '' }} />
                <span></span>@lang('main.' . $paymentType['name'])
            </label>
        @endforeach
    </div>
    <div class="label-with-desc client-pay-type-desc">
        <div class="desc-content"></div>
    </div>

    <span class="js-validation-error invalid-feedback d-none" role="alert"><strong>@lang('main.Enter client pay type')</strong></span>
</div>
{{--./ client pay type --}}

{{-- confirm first service booked --}}
<div class="form-group confirm-service-book-container" style="display: none">
    <div class="label-with-desc">
        <label>@lang("main.Do you want to confirm first service booked?") <span class="text-danger">*</span></label>
    </div>
    
    <div class="radio-inline confirm_first_service_book">
        <label class="radio mr-8">
            <input type="radio" name="confirm_first_service_book" value="1"
                {{ old('confirm_first_service_book', $service->booking_confirm ?? null) == Service::BOOKING_DIRECTLY ? 'checked' : '' }}/>
            <span></span>@lang('main.No, I will allow to book directly')
        </label>
        <label class="radio mr-8">
            <input type="radio" name="confirm_first_service_book" value="2"
                {{ old('confirm_first_service_book', $service->booking_confirm ?? null) == Service::BOOKING_CONFIRM ? 'checked' : '' }}/>
            <span></span>@lang('main.Yes, I want to confirm it first')
        </label>
    </div>

    <div class="label-with-desc confirm-first-book-desc">
        <div class="desc-content"></div>
    </div>

    <span class="js-validation-error invalid-feedback d-none" role="alert"><strong>@lang('main.Enter confirm first service booked')</strong></span>
</div>
{{--./ confirm first service booked --}}

<div class="service-price-container" style="display: none">
    {{-- Price --}}
    <div class="row">
        <div class="col-md-6">
            <div class="form-group">
                <label>@lang('main.Enter price for single service') <span class="text-danger">*</span> €</label>
                <input type="text" name="offline_price" class="input-decimal form-control form-control-lg @error('price') is-invalid @enderror"
                    placeholder="@lang('main.Enter price per single service')" value="{{ old('offline_price', $service->price ?? '') }}" />
                
                @include('user.components.validation-error', ['field' => 'offline_price'])
                <span class="js-validation-error invalid-feedback d-none" role="alert"><strong>@lang('main.Enter price for the service')</strong></span>
            </div>
        </div>
    </div>
    {{--./ Price --}}

    {{-- Discount Percentage --}}
    <div class="row discount-container" style="display: none">
        <div class="col-md-6">
            <div class="form-group">
                <div class="label-with-desc">
                    <label>@lang("main.Do you want to offer a discount percentage per single service?") </label>
                    <div class="desc-content">@lang('main.Discount is available only if customer pay online')</div>
                </div>
                <div class="input-group">
                    <input type="number" name="offline_discount_percentage_single" class="form-control form-control-lg @error('offline_discount_percentage_single') is-invalid @enderror"
                        placeholder="@lang('main.Enter discount percentage per single service')"
                        value="{{ old('offline_discount_percentage_single', $discountSingle) }}"
                        step="1" />
                    <div class="input-group-append"><span class="input-group-text">%</span></div>
                </div>
                
                @include('user.components.validation-error', ['field' => 'offline_discount_percentage_single'])
                <span class="js-validation-error invalid-feedback d-none" role="alert">@lang('main.Please enter greater than 5')</span>
            </div>
        </div>
    </div>
    {{--./ Discount Percentage --}}

    {{-- Extra Discount Percentage --}}
    <div class="form-group offline-extra-discount-container" style="display: none">
        <div class="label-with-desc">
            <label>@lang("main.Do you want to offer a discount percentage if customer book more than one service?") </label>
            <div class="desc-content">@lang('main.Discount is available only if customer pay online and order more than one service')</div>
        </div>
        <div class="row">
            <div class="col-md-6">
                <div class="input-group">
                    <input type="number" name="offline_discount_percentage_multiple" class="form-control form-control-lg @error('offline_discount_percentage_multiple') is-invalid @enderror"
                        placeholder="@lang('main.Enter discount percentage if order more than a service')"
                        value="{{ old('offline_discount_percentage_multiple', $discountMultiple) }}"
                        step='0.1' />
                    <div class="input-group-append"><span class="input-group-text">%</span></div>
                </div>
            
                @include('user.components.validation-error', ['field' => 'offline_discount_percentage_multiple'])
                <span class="js-validation-error invalid-feedback d-none" role="alert">@lang('main.Please enter greater than 5')</span>
            </div>
        </div>
    </div>
    {{--./ Extra Discount Percentage --}}

    <div class="extra-price-option-container" style="display: none">
        {{-- extra cost off-site option --}}
        <div class="form-group">
            <label>@lang("main.Do you will charge an extra cost to go off-site?") <span class="text-danger">*</span></label><br />
            
            <div class="radio-inline extra_price_type">
                @foreach($extra_price_types as $extraCostType)
                    <label class="radio mr-8">
                        <input type="radio" name="extra_price_type" value="{{ $extraCostType['type_id'] }}"
                            {{ old('extra_price_type', $service->extra_price_type ?? null) == $extraCostType['type_id'] ? 'checked' : '' }} />
                        <span></span>@lang('main.' . $extraCostType['name'])
                    </label>
                @endforeach
            </div>

            <div class="label-with-desc extra-price-desc extra-price-container" style="display: none">
                <div class="desc-content">@lang('main.Extra cost off-site travel will be paid by client on booking day')</div>
            </div>

            <span class="js-validation-error invalid-feedback d-none" role="alert"><strong>@lang('main.Enter client pay type')</strong></span>
        </div>
        {{--./ extra cost off-site option --}}

        {{-- extra cost --}}
        <div class="form-group extra-price-container" style="display: none">
            <div class="row">
                <div class="col-md-6">
                    <input type="text" name="extra_price" class="input-decimal form-control form-control-lg @error('extra_price') is-invalid @enderror"
                        placeholder="@lang('main.Enter extra price for off-site')"
                        value="{{ old('extra_price', $service->extra_price ?? '') }}" />

                    @include('user.components.validation-error', ['field' => 'extra_discount_percentage'])
                    <span class="js-validation-error invalid-feedback d-none" role="alert"><strong>@lang('main.Enter extra price')</strong></span>
                </div>
            </div>
        </div>
        {{--./ extra cost --}}
    </div>
</div>