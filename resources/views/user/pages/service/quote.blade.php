<form class="form" method="POST" action="{{ route('user.service.quote.store') }}">
    @csrf

    <div class="pb-5" data-wizard-type="step-content" data-wizard-state="current">
        <h3 class="mb-10 font-weight-bold text-dark">@lang('main.Quotations title')</h3>
        <p class="text-muted">@lang('main.Quotations wizard description')</p>
        <!-- invoice header -->
        <div class="form-group">
            <label>@lang('Quote Info') <span class="text-danger">*</span></label>
            <input type="text" name="company_name" class="form-control form-control-lg @error('company_name') is-invalid @enderror"
                placeholder="@lang('Invoice header')" value="" />
            
            @include('user.components.validation-error', ['field' => 'company_name'])
        </div>
        <!--./ invoice hdeader -->

        <div class="row">
            <div class="col-xl-6">
                <!-- type of company -->
                <div class="form-group">
                    <label>@lang('Quote Info 2') <span class="text-danger">*</span></label>
                    <select name="" class="form-control form-control-lg">
                        <option value="">@lang('Select type of Company')</option>
                        <option value="1">Quote Select 1</option>
                        <option value="2">Quote Select 2</option>
                    </select>

                    @include('user.components.validation-error', ['field' => 'company_type_id'])
                </div>
                <!--./ type of company -->
            </div>
            <div class="col-xl-6">
                <!-- region -->
                <div class="form-group">
                    <label>@lang('Quote Info 3') <span class="text-danger">*</span></label>
                    <input type="text" name="quote_info2" class="form-control form-control-lg @error('invoice_vat_id') is-invalid @enderror"
                        placeholder="@lang('VAT ID / EIN')" value="" />

                    @include('user.components.validation-error', ['field' => 'invoice_vat_id'])
                </div>
                <!--./ region -->
            </div>
        </div>
    </div>

    <!--begin::Wizard Actions-->
    <div class="d-flex justify-content-between border-top mt-5 pt-10">
        <div class="mr-2">
            <a href="{{ route('user.profile.wizard', 'contact') }}" class="d-block btn btn-light-primary font-weight-bold px-9 py-2">@lang('main.Previous')</a>
        </div>
        <div>
            <a href="{{ route('user.profile.wizard', 'service') }}" class="btn btn-secondary font-weight-bold px-9 py-2">@lang('main.Skip')</a>
            <button type="submit" name="save_next" value="save_next" class="btn btn-primary font-weight-bold px-9 py-2">@lang('main.Next')</button>
        </div>
    </div>
    <!--end::Wizard Actions-->
</form>