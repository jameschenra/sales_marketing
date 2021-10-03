@php
    use App\Models\Country;
@endphp

<div class="row">
    <div class="col-xl-6">
        <!-- country -->
        <div class="form-group">
            <label>@lang('main.Country') <span class="text-danger">*</span></label>
            <select name="country_id" class="form-control selectpicker @error('country_id') is-invalid @enderror"
                data-size="7" data-live-search="true">
                <option value="">@lang('main.select country')</option>
                @foreach($countries as $c)
                    <option value="{{$c->id}}" {{$c->id == old('country_id', $user->detail->country_id ?: Country::COUNTRY_ITALY) ? 'selected':''}}>{{ $c->short_name }}</option>
                @endforeach
            </select>

            @include('user.components.validation-error', ['field' => 'country_id'])
            <span class="js-validation-error invalid-feedback d-none" role="alert">{{ trans('main.Select Country') }}</span>
        </div>
        <!--./ country -->
    </div>

    <div class="col-xl-6">
        <!-- language -->
        <div class="form-group">
            <label>@lang('main.select language') <span class="text-danger">*</span></label>
            <select name="language[]" id="sel-language" multiple="multiple"
                class="form-control form-control-lg @error('language') is-invalid @enderror">
                @foreach($languages as $lng)
                    <option value="{{$lng->code}}" {{ in_array($lng->code, $userLanguages)  ? 'selected':''}}>{{ $lng->name }}</option>
                @endforeach
            </select>

            @include('user.components.validation-error', ['field' => 'language'])
            <span class="js-validation-error invalid-feedback d-none" role="alert">{{ trans('main.Select Language') }}</span>
        </div>
        <!--./ language -->
    </div>
</div>

<hr />
<div id="profession-container">
    <div class="form-group">
        <label>@lang('main.Profession') <span class="text-danger">*</span></label>
        <input id="input-professions" class="form-control tagify @error('profession') is-invalid @enderror"
            name="professions" placeholder='Add professions...' value=""/>

        @include('user.components.validation-error', ['field' => 'professions'])
        <span class="js-validation-error invalid-feedback d-none" role="alert">{{ trans('main.Select at least a Profession') }}</span>
    </div>

    <div class="form-group pl-4">
        <a href="javascript:openProfessionDlg()" class="btn btn-icon btn-success mr-2">
            <i class="flaticon2-plus"></i>
        </a> @lang('main.You can enter up to 10 Professions')
    </div>
</div>