{{-- Service Name --}}
<div class="form-group">
    <label>@lang('main.Service Name') <span class="text-danger">*</span></label>
    <ul class="nav nav-tabs" id="name-tab-links" role="tablist">
        {{-- default name tab link --}}
        <li class="nav-item">
            <a class="nav-link active" id="{{ $default_lang }}-name-tab" data-toggle="tab" href="#name-{{ $default_lang }}" role="tab" aria-controls="name-{{ $default_lang }}" aria-selected="true">{{ ucfirst($support_locales[$default_lang]['native']) }}</a>
        </li>
        {{--./ default name tab link --}}

        <li class="nav-item">
            <a class="nav-link disabled" data-toggle="tab" href="#" role="tab" aria-controls="@lang('main.add the translation also in')" aria-selected="false">@lang('main.add the translation also in')</a>
        </li>

        {{-- other name tab link --}}
        @foreach($support_locales as $localeCode => $locale)
            @if ($localeCode != $default_lang)
                <li class="nav-item">
                    <a class="nav-link" id="{{ $localeCode }}-name-tab" data-toggle="tab" href="#name-{{ $localeCode }}" role="tab" aria-controls="name-{{ $localeCode }}" aria-selected="true">{{ ucfirst($locale['native']) }}</a>
                </li>
            @endif
        @endforeach
        {{--./ other name tab link --}}
    </ul>

    <div class="tab-content" id="name-tab-content">
        {{-- default name input --}}
        <div class="tab-pane fade show active" id="name-{{ $default_lang }}" role="tabpanel" aria-labelledby="{{ $default_lang }}-name-tab">
            <input type="text" name="name_{{ $default_lang }}" class="form-control form-control-lg profile-desc @error('name_' . $default_lang) is-invalid @enderror"
                placeholder="@lang('main.Service Name')" value="{{ old('name_' . $default_lang, $service->{'name_' . $default_lang} ?? '') }}" />

            @include('user.components.validation-error', ['field' => 'name_' . $default_lang])
            <span class="js-validation-error invalid-feedback d-none" role="alert"><strong>{{ trans('main.Enter a service name') }}</strong></span>
        </div>
        {{-- default name input --}}

        {{-- other name input --}}
        @foreach($support_locales as $localeCode => $locale)
            @if ($localeCode != $default_lang)
                <div class="tab-pane fade" id="name-{{ $localeCode }}" role="tabpanel" aria-labelledby="{{ $localeCode }}-name-tab">
                    <input type="text" name="name_{{ $localeCode }}" class="form-control form-control-lg profile-desc @error('name_' . $localeCode) is-invalid @enderror"
                        placeholder="@lang('main.Service Name')" value="{{ old('name_' . $localeCode, $service->{'name_' . $localeCode} ?? '') }}" />
                    
                    @include('user.components.validation-error', ['field' => 'name_' . $localeCode])
                </div>
            @endif
        @endforeach
        {{--./ other name input --}}
    </div>
</div>
{{--./ Service Name --}}

<div class="row">
    {{-- Category --}}
    <div class="col-md-6">
        <div class="form-group">
            <label>@lang('main.Category of services') <span class="text-danger">*</span></label>
            <select name="category_id" id="sel-category-id" class="form-control form-control-lg @error('category_id') is-invalid @enderror">
                <option value="">@lang('main.Select a category of services')</option>
                @foreach($categories as $cat)
                    <option value="{{$cat->id}}" {{$cat->id == $categoryId ? 'selected':''}} data-photo="{{ $cat->image }}">{{$cat->name}}</option>
                @endforeach
            </select>

            @include('user.components.validation-error', ['field' => 'category_id'])
            <span class="js-validation-error invalid-feedback d-none" role="alert"><strong>{{ trans('main.Enter a service category') }}</strong></span>
        </div>
    </div>
    {{--./ Category --}}

    {{-- Sub Category --}}
    <div class="col-md-6">
        <div class="form-group">
            <label>@lang('main.Specific sector') <span class="text-danger">*</span></label>
            <select name="sub_category_id" id="sel-sub-category-id" class="form-control form-control-lg @error('sub_category_id') is-invalid @enderror">
                <option value="">@lang('main.Select a specific sector')</option>
                @foreach($oldSubCategories as $subCat)
                    <option value="{{$subCat->id}}" {{$subCat->id == old('sub_category_id', $service->sub_category_id ?? '') ? 'selected':''}}>{{ $subCat->name }}</option>    
                @endforeach
            </select>

            @include('user.components.validation-error', ['field' => 'sub_category_id'])
            <span class="js-validation-error invalid-feedback d-none" role="alert"><strong>{{ trans('main.Enter a specific sector') }}</strong></span>
        </div>
    </div>
    {{--./ Sub Category --}}
</div>

{{-- Description --}}
<div class="form-group">
    <label>@lang('main.Description') <span class="text-danger">*</span></label>
    <ul class="nav nav-tabs" id="desc-tab-links" role="tablist">
        {{-- default description tab link --}}
        <li class="nav-item">
            <a class="nav-link active" id="{{ $default_lang }}-desc-tab" data-toggle="tab" href="#desc-{{ $default_lang }}" role="tab" aria-controls="desc-{{ $default_lang }}" aria-selected="true">{{ ucfirst($support_locales[$default_lang]['native']) }}</a>
        </li>
        {{--./ default description tab link --}}

        <li class="nav-item">
            <a class="nav-link disabled" data-toggle="tab" href="#" role="tab" aria-controls="@lang('main.add the translation also in')" aria-selected="false">@lang('main.add the translation also in')</a>
        </li>

        {{-- other language description tab link --}}
        @foreach($support_locales as $localeCode => $locale)
            @if ($localeCode != $default_lang)
                <li class="nav-item">
                    <a class="nav-link" id="{{ $localeCode }}-desc-tab" data-toggle="tab" href="#desc-{{ $localeCode }}" role="tab" aria-controls="desc-{{ $localeCode }}" aria-selected="false">{{ ucfirst($support_locales[$localeCode]['native']) }}</a>
                </li>
            @endif
        @endforeach
        {{--./ other language description tab link --}}
    </ul>

    <div class="tab-content" id="description-tab-content">
        {{-- default name input --}}
        <div class="tab-pane fade show active" id="desc-{{ $default_lang }}" role="tabpanel" aria-labelledby="{{ $default_lang }}-desc-tab">
            <textarea name="description_{{ $default_lang }}" class="form-control profile-desc text-check-count 
                @error('description_' . $default_lang) is-invalid @enderror" minValidLength="5000" minlength="100"
                placeholder="{{ trans('main.placeholder service', [], $default_lang) }}" cols="120" rows="8">{{ old('description_' . $default_lang, $service->{'description_' . $default_lang} ?? '') }}</textarea>
            <div class="counter-status text-right text-muted"><span class="current-count"></span> / <span class="total-count"></span></div>

            @include('user.components.validation-error', ['field' => 'description_' . $default_lang])
            <span class="js-validation-error invalid-feedback d-none" role="alert"><strong>{{ trans('main.Enter a service description') }}</strong></span>
        </div>
        {{--./ default name input --}}

        {{-- other language name input --}}
        @foreach($support_locales as $localeCode => $locale)
            @if ($localeCode != $default_lang)
                <div class="tab-pane fade" id="desc-{{ $localeCode }}" role="tabpanel" aria-labelledby="{{ $localeCode }}-desc-tab">
                    <textarea name="description_{{ $localeCode }}" class="form-control profile-desc"
                        placeholder="{{ trans('main.placeholder service', [], $localeCode) }}"
                        cols="120" rows="10">{{ old('description_' . $localeCode, $service->{'description_' . $localeCode} ?? '') }}</textarea>
                    
                    @include('user.components.validation-error', ['field' => 'description_' . $localeCode])
                </div>
            @endif
        @endforeach
        {{--./ other language name input --}}
    </div>
</div>
{{--./ Description --}}

