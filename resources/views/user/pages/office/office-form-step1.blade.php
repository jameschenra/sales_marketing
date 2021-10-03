<!-- office name -->
<div class="row">
    <div class="col-md-6 form-group">
        <label>@lang('main.Office name') <span class="text-danger">*</span></label>
        <input type="text" name="name" class="form-control form-control-lg @error('name') is-invalid @enderror"
            placeholder="@lang('main.Enter office name')" value="{{ old('name', $office->name ?? '') }}" />
        <p class="desc-content">@lang('main.main.office wizard text under name')</p>
    
        @include('user.components.validation-error', ['field' => 'name'])
        <span class="js-validation-error invalid-feedback d-none" role="alert"><strong>{{ trans('main.Enter a office name') }}</strong></span>
    </div>
</div>
<!--./ office name -->

<!-- phone number -->
<div class="row">
    <div class="col-md-6 form-group">
        <label>@lang('main.Office telephone') <span class="text-danger">*</span></label>
        <input type="text" name="phone_number" id="phone" class="form-control form-control-lg @error('phone_number') is-invalid @enderror"
            placeholder="@lang('main.Enter office telephone')" value="{{ $officePhoneNumber }}" />
        <p class="desc-content">@lang('main.office wizard text under telephone')</p>
        
        @include('user.components.validation-error', ['field' => 'phone_number', 'custom-control' => true])
        <span class="js-validation-error invalid-feedback d-none" role="alert"><strong>{{ trans('main.Enter phone number') }}</strong></span>
    </div>
</div>
<!--./ phone number -->