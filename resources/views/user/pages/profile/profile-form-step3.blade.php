<div class="form-group">
    <label>@lang('main.About Me') <span class="text-danger">*</span></label>
    <ul class="nav nav-tabs" id="about-tab-links" role="tablist">
        <li class="nav-item">
            <a class="nav-link active" id="{{ $defaultLang }}-desc-tab" data-toggle="tab" href="#desc-{{ $defaultLang }}"
                role="tab" aria-controls="desc-{{ $defaultLang }}" aria-selected="true">{{ ucfirst($supportLocales[$defaultLang]['native']) }}</a>
        </li>

        @foreach ($supportLocales as $localeCode => $locale)
            @if ($localeCode != $defaultLang)
                <li class="nav-item">
                    <a class="nav-link" id="{{ $localeCode }}-desc-tab" data-toggle="tab" href="#desc-{{ $localeCode }}" role="tab" aria-controls="desc-{{ $localeCode }}" aria-selected="false">{{ ucfirst($supportLocales[$localeCode]['native']) }}</a>
                </li>
            @endif
        @endforeach
    </ul>

    <div class="tab-content" id="about-tab-content">
        <div class="tab-pane fade show active" id="desc-{{ $defaultLang }}" role="tabpanel" aria-labelledby="{{ $defaultLang }}-desc-tab">
            <textarea name="description_{{ $defaultLang }}" class="form-control profile-desc text-check-count 
                @error('description_' . $defaultLang) is-invalid @enderror" minValidLength="5000" minlength="300"
                placeholder="{{ trans('main.placeholder profile', [], $defaultLang) }}" cols="120" rows="10">{{ old('description_' . $defaultLang, $user->detail->{'description_' . $defaultLang}) }}</textarea>
            <div class="counter-status text-right text-muted"><span class="current-count"></span> / <span class="total-count"></span></div>

            @include('user.components.validation-error', ['field' => 'description_' . $defaultLang])
        </div>

        @foreach($supportLocales as $localeCode => $locale)
            @if ($localeCode != $defaultLang)
                <div class="tab-pane fade" id="desc-{{ $localeCode }}" role="tabpanel" aria-labelledby="{{ $localeCode }}-desc-tab">
                    <textarea class="form-control profile-desc text-check-count" name="description_{{ $localeCode }}"
                        placeholder="{{ trans('main.placeholder profile', [], $localeCode) }}" minValidLength="5000"
                        cols="120" rows="10">{{ old('description_' . $localeCode, $user->detail->{'description_' . $localeCode}) }}</textarea>
                    <div class="counter-status text-right text-muted"><span class="current-count"></span> / <span class="total-count"></span></div>
                    
                    @include('user.components.validation-error', ['field' => 'description_' . $localeCode])
                </div>
            @endif
        @endforeach
    </div>

    <span class="js-validation-error invalid-feedback d-none" role="alert">@lang("main.Enter description")</span>
</div>
