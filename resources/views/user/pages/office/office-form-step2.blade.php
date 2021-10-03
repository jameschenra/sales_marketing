<div class="row">
    <!-- country -->
    <div class="col-xl-6">
        <div class="form-group">
            <label>@lang('main.Country') <span class="text-danger">*</span></label>
            <select name="country_id" class="form-control selectpicker @error('country_id') is-invalid @enderror"
                data-size="7" data-live-search="true">
                <option value="">@lang('main.Select office country')</option>
                @foreach($countries as $country)
                    <option value="{{$country->id}}" {{$country->id == $officeCountryId ? 'selected':''}}>{{ $country->short_name }}</option>
                @endforeach
            </select>

            @include('user.components.validation-error', ['field' => 'country_id'])
            <span class="js-validation-error invalid-feedback d-none" role="alert"><strong>{{ trans('main.Select Country') }}</strong></span>
        </div>
    </div>
    <!--./ country -->

    <!-- region -->
    <div class="col-xl-6">
        <div class="form-group">
            <label>@lang('main.region') <span class="text-danger">*</span></label>
            <select name="region_id" class="form-control selectpicker @error('region_id') is-invalid @enderror"
                data-size="7" data-live-search="true">
                <option value="">@lang('main.Enter office region')</option>
            </select>
            
            @include('user.components.validation-error', ['field' => 'region_id'])
            <span class="js-validation-error invalid-feedback d-none" role="alert"><strong>{{ trans('main.Enter Region') }}</strong></span>
        </div>
    </div>
    <!--./ region -->
</div>

<div class="row">
    <!-- city -->
    <div class="col-xl-6">
        <div class="form-group">
            <label>@lang('main.city') <span class="text-danger">*</span></label>
            <select name="city_id" class="form-control selectpicker @error('city_id') is-invalid @enderror"
                data-size="7" data-live-search="true">
                <option value="">@lang('main.Enter office city')</option>
            </select>

            @include('user.components.validation-error', ['field' => 'city_id'])
            <span class="js-validation-error invalid-feedback d-none" role="alert"><strong>{{ trans('main.Enter City') }}</strong></span>
        </div>
    </div>
    <!--./ city -->

    <!-- address -->
    <div class="col-xl-6">
        <div class="form-group">
            <label>@lang('main.Office address') <span class="text-danger">*</span></label>
            <input type="text" name="address" class="form-control form-control-lg @error('address') is-invalid @enderror"
                placeholder="@lang('main.Office address')" value="{{ old('address', $office->address ?? '') }}" />
            
            @include('user.components.validation-error', ['field' => 'address'])
            <span class="js-validation-error invalid-feedback d-none" role="alert"><strong>{{ trans("main.Enter Address") }}</strong></span>
        </div>
    </div>
    <!--./ address -->
</div>

<div class="row">
    <!-- zip code -->
    <div class="col-xl-6">
        <div class="form-group">
            <label>@lang('main.Zip Code') <span class="text-danger">*</span></label>
            <input type="text" name="zip_code" class="form-control form-control-lg @error('zip_code') is-invalid @enderror" 
                placeholder="@lang('main.Zip Code')" value="{{ old('zip_code', $office->zip_code ?? '') }}" />

            @include('user.components.validation-error', ['field' => 'zip_code'])
            <span class="js-validation-error invalid-feedback d-none" role="alert"><strong>{{ trans('main.Enter zip code') }}</strong></span>
        </div>
    </div>
    <!--./ zip code -->
</div>

<div class="form-group">
    <div id="map"></div>
    <input type="hidden" name="lat"/>
    <input type="hidden" name="lng"/>
</div>