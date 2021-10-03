@php
    $supportedLocales = LaravelLocalization::getSupportedLocales();
    if (isset($company)) {
        $name = old('name', $company->name);
        $email = old('email', $company->email);
        $phone = old('phone', $company->phone);
        $countryId = old('country', $company->detail->country_id);
        $language = old('language', $company->default_language);
        $photo = old('photo', $company->detail->photo);
        $photoImg = old('photo', $company->detail->photo);
        $description_en = old('description_en', $company->detail->description_en);
        $description_es = old('description_es', $company->detail->description_es);
        $description_it = old('description_it', $company->detail->description_it);
        $enrollType = old('enroll_type', $company->detail->enroll_type);
    } else {
        $name = old('name');
        $email = old('email');
        $phone = old('phone');
        $language = old('language');
        $countryId = old('country');
        $photo = old('photo');
        $photoImg = old('photo', 'blank.png');
        $description_en = old('description_en');
        $description_es = old('description_es');
        $description_it = old('description_it');
        $enrollType = old('enroll_type');
    }
@endphp

{{-- Extends layout --}}
@extends('admin.layout.default')

{{-- Content --}}
@section('content')

<div class="row justify-content-center">
    <div class="col-lg-8">
        @include('admin.components.validation-error')

        <div class="card">
            <form method="POST" action="{{ route('admin.company.store') }}" enctype="multipart/form-data">
                @csrf
                @isset($company)
                    <input type="hidden" name="company_id" value="{{ $company->id }}" />
                @endisset

                <div class="card-body">
                    {{-- email --}}
                    <div class="form-group row">
                        <label class="col-sm-3 col-form-label text-right">{{ trans('main.Name') }}</label>
                        <div class="col-sm-9">
                            <input type="text" class="form-control" name="name" value="{{ $name }}">
                        </div>
                    </div>
                    {{--./ email --}}

                    {{-- email --}}
                    <div class="form-group row">
                        <label class="col-sm-3 col-form-label text-right">{{ trans('main.Email') }}</label>
                        <div class="col-sm-9">
                            <input type="email" class="form-control" name="email" value="{{ $email }}">
                        </div>
                    </div>
                    {{--./ email --}}

                    {{-- category --}}
                    <div class="form-group row">
                        <label class="col-sm-3 col-form-label text-right">{{ trans('main.Category') }}</label>
                        <div class="col-md-9">
                            <select id="cat_sel" multiple="multiple" name="profession[]" class="form-control">
                                @foreach ($categories as $category)
                                <optgroup label="{{ $category->name }}">
                                    @foreach ($category->professions as $profession)
                                        <option value="{{$profession->id}}">&nbsp; {{ $profession->name }}</option>
                                    @endforeach
                                </optgroup>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    {{--./ category --}}

                    {{-- password --}}
                    <div class="form-group row">
                        <label class="col-sm-3 col-form-label text-right">{{ trans('main.Password') }}</label>
                        <div class="col-sm-9">
                            <input type="password" class="form-control" name="password">
                        </div>
                    </div>
                    {{--./ password --}}

                    {{-- country --}}
                    <div class="form-group row">
                        <label class="col-sm-3 col-form-label text-right">{{ trans('main.Country') }}</label>
                        <div class="col-md-9">
                            <select name="country" class="form-control">
                                @foreach ($countries as $country)
                                    <option value="{{$country->id}}" {{$country->id == $countryId ? 'selected':''}}>{{ $country->short_name }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    {{--./ country --}}

                    {{-- language --}}
                    <div class="form-group row">
                        <label class="col-sm-3 col-form-label text-right">{{ trans('main.Languages') }}</label>
                        <div class="col-md-9">
                            <select id="lng_sel" multiple="multiple" name="languages[]" class="form-control">
                                @foreach ($languages as $language)
                                    <option value="{{$language->code}}" {{$language->code == $language ? 'selected':''}}>{{ $language->name }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    {{--./ language --}}

                    {{-- phone --}}
                    <div class="form-group row">
                        <label class="col-sm-3 col-form-label text-right">{{ trans('main.Phone') }}</label>
                        <div class="col-sm-9">
                            <input type="text" class="form-control" name="phone" value="{{ $phone }}">
                        </div>
                    </div>
                    {{--./ phone --}}

                    {{-- rate --}}
                    <div class="form-group row">
                        <label class="col-sm-3 col-form-label text-right">{{ trans('main.Hourly Rate') }}</label> @lang('main.is_optional')
                        <div class="col-sm-9">
                            <input type="text" class="form-control" name="rate">
                        </div>
                    </div>
                    {{--./ rate --}}

                    {{-- enrolled at --}}
                    <div class="form-group row">
                        <label class="col-sm-3 col-form-label text-right">{{ trans('main.Registered to') }}</label>
                        <div class="col-md-9">
                            <select id="enroll_sel" name="enroll_type" class="form-control">
                                @foreach($enroll_types as $enroll)
                                    <option value="{{ $enroll->id }}" {{ $enrollType ==  $enroll->id ? 'selected':''}}>{{ $enroll->name }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    {{--./ enrolled at --}}

                    {{-- is_active --}}
                    <div class="form-group row">
                        <label class="col-sm-3 col-form-label text-right">{{ trans('main.Active') }}</label>
                        <div class="col-md-9">
                            <select id="enroll_sel" name="is_active" class="form-control">
                                <option value="1">{{ trans('main.Active') }}</option>
                                <option value="0">{{ trans('main.Inactive') }}</option>
                            </select>
                        </div>
                    </div>
                    {{--./ is_active --}}

                    {{-- city --}}
                    <div class="form-group row">
                        <label class="col-sm-3 col-form-label text-right">{{ trans('main.City of College or Order') }}</label>
                        <div class="col-sm-9">
                            <input type="text" class="form-control" name="city">
                        </div>
                    </div>
                    {{--./ city --}}

                    {{-- reg number --}}
                    <div class="form-group row">
                        <label class="col-sm-3 col-form-label text-right">{{ trans('main.Registration Number') }}</label>
                        <div class="col-sm-9">
                            <input type="text" class="form-control" name="user_key">
                        </div>
                    </div>
                    {{--./ reg number --}}

                    {{-- vat id --}}
                    <div class="form-group row">
                        <label class="col-sm-3 col-form-label text-right">{{ trans('main.VAT ID') }}</label>
                        <div class="col-sm-9">
                            <input type="text" class="form-control" name="vat_id">
                        </div>
                    </div>
                    {{--./ vat id --}}

                    {{-- keyword --}}
                    <div class="form-group row">
                        <label class="col-sm-3 col-form-label text-right">{{ trans('main.Keyword') }}</label>
                        <div class="col-sm-9">
                            <input type="text" class="form-control" name="keyword">
                        </div>
                    </div>
                    {{--./ keyword --}}

                    {{-- photo --}}
                    <div class="form-group row">
                        <label class="col-sm-3 col-form-label text-right">{{ trans('main.photo') }}</label>
                        <div class="col-sm-9">
                            <div class="image-input image-input-outline" id="company_photo">
                                <div class="image-input-wrapper" style="background-image: url(/upload/user/{{ $photoImg }})"></div>

                                <label class="btn btn-xs btn-icon btn-circle btn-white btn-hover-text-primary btn-shadow" data-action="change" data-toggle="tooltip" title="" data-original-title="Change avatar">
                                <i class="fa fa-pen icon-sm text-muted"></i>
                                <input type="file" name="photo" accept=".png, .jpg, .jpeg" value="{{ $photo }}"/>
                                <input type="hidden" name="photo_remove"/>
                                </label>

                                <span class="btn btn-xs btn-icon btn-circle btn-white btn-hover-text-primary btn-shadow" data-action="cancel" data-toggle="tooltip" title="Cancel avatar">
                                <i class="ki ki-bold-close icon-xs text-muted"></i>
                                </span>
                            </div>
                        </div>
                    </div>
                    {{--./ photo --}}

                    {{-- name --}}
                    @if($supportedLocales)
                        @foreach($supportedLocales as $localeCode => $properties)
                            <div class="form-group row">
                                <label class="col-sm-3 col-form-label text-right">{{ trans('main.Description').' ('.$properties['native'].')' }}</label>
                                <div class="col-sm-9">
                                    <textarea class="form-control" name="{{ 'description_' . $localeCode }}" rows="3">{{ ${'description_' . $localeCode} }}</textarea>
                                </div>
                            </div>
                        @endforeach
                    @endif
                    {{--./ name --}}

                </div>
                
                <div class="card-footer">
                    <button type="submit" class="btn btn-success font-weight-bold mr-2 float-right">@lang('main.Submit')</button>
                    <a href="{{ route('admin.company.index') }}" class="btn btn-light-success font-weight-bold">@lang('main.Back')</a>
                </div>
            </form>
        </div>
    </div>
</div>


@endsection

{{-- Scripts Section --}}
@section('scripts')
<script>
    new KTImageInput('company_photo');
</script>
@endsection
