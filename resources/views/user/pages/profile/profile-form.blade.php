@php
    use App\Models\EnrollType;
    use App\Enums\UserType;

    $user = auth()->user();
    $isSeller = ($user->type == UserType::SELLER);
    $defaultLang = $user->default_language ?: app()->getLocale();

    if ($isSeller) {
        $supportLocales = \LaravelLocalization::getSupportedLocales();

        $userProfessions = [];

        if ($user->profsByUser) {
            foreach ($user->profsByUser as $prof) {
                $userProfessions[] = [
                    'value' => $prof->profession->name,
                    'category_id' => $prof->profession_category_id,
                    'profession_id' => $prof->profession_id,
                ];
            }
        }

        $professionCatList = [];
        foreach ($professions as $profCategory) {
            $professionCatList[$profCategory->id] = [];
            foreach ($profCategory['professions'] as $prof) {
                $professionCatList[$profCategory->id][] = [
                    'id' => $prof->id,
                    'name' => $prof->name,
                ];
            }        
        }

        $userLanguages = old('language', $user->detail->languages ?: []);
        if ($userLanguages && !is_array($userLanguages)) {
            $userLanguages = explode(',', $userLanguages);
        }

        $associationId = old('association_id', $user->detail->association_id);
        $curEnrollType = old('enroll_type', $user->detail->enroll_type);
    }

    $userPhoto = old('photo', $user->detail->photo);
@endphp

@section('partial-styles')
    @include('user.include-plugins.input-tel.input-tel-css')
    {{ Html::style(userAsset('pages/service/step-wizard.css')) }}
    <style>
        .tagify__input {
            display: none;
        }
    </style>
@endsection

@include('user.components.validation-top-error')

<form class="form" method="POST" action="{{ route('user.profile.store') }}" enctype="multipart/form-data" id="profile-form">
    @csrf

    <div class="pb-5" data-wizard-type="step-content" data-wizard-state="current">
        <h3 class="mb-10 font-weight-bold text-dark">@lang('main.Edit Profile')</h3>
        <div class="row">
            <div class="col-xl-6">
                <!-- first name -->
                <div class="form-group">
                    <label>@lang('main.Name') <span class="text-danger">*</span></label>
                    <input type="text" name="name" class="form-control form-control-lg @error('name') is-invalid @enderror"
                        placeholder="@lang('main.Name')" value="{{ old('name', $user['name']) }}" />

                    @include('user.components.validation-error', ['field' => 'name'])
                </div>
                <!--./ first name -->
            </div>
            <div class="col-xl-6">
                <!-- last name -->
                <div class="form-group">
                    <label>@lang('main.Surname') <span class="text-danger">*</span></label>
                    <input type="text" name="last_name" class="form-control form-control-lg @error('last_name') is-invalid @enderror"
                        placeholder="@lang('main.Surname')" value="{{ old('last_name', $user['last_name']) }}" />

                    @include('user.components.validation-error', ['field' => 'last_name'])
                </div>
                <!--./ last name -->
            </div>
        </div>

        <div class="row">
            <div class="col-xl-6">
                <!-- first name -->
                <div class="form-group">
                    <label>@lang('main.Email') <span class="text-danger">*</span></label>
                    <input type="email" name="email" class="form-control form-control-lg @error('email') is-invalid @enderror"
                        placeholder="@lang('main.Email Address')" value="{{ $user['email'] }}" disabled="disabled" />
                </div>
                <!--./ first name -->
            </div>
            <div class="col-xl-6">
                <!-- last name -->
                <div class="form-group">
                    <label>@lang('main.Phone') <span class="text-danger">*</span></label>
                    <input type="text" name="phone" id="phone" class="input-decimal form-control form-control-lg @error('phone') is-invalid @enderror"
                        placeholder="@lang('main.Phone Number')" value="{{ old('phone', $user['phone']) }}" />

                    @include('user.components.validation-error', ['field' => 'phone', 'custom-control' => true])
                </div>
                <!--./ last name -->
            </div>
        </div>
        
        <div class="row">
            <div class="col-xl-6">
                <!-- country -->
                <div class="form-group">
                    <label>@lang('main.Country') <span class="text-danger">*</span></label>
                    <select name="country_id" class="form-control form-control-lg @error('country_id') is-invalid @enderror">
                        <option value="">@lang('main.select country')</option>
                        @foreach($countries as $c)
                            <option value="{{$c->id}}" {{$c->id == old('country_id', $user->detail->country_id) ? 'selected':''}}>{{ $c->short_name }}</option>
                        @endforeach
                    </select>

                    @include('user.components.validation-error', ['field' => 'country_id'])
                </div>
                <!--./ country -->
            </div>

            @if ($isSeller)
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
                    </div>
                    <!--./ language -->
                </div>
            @endif
        </div>

        @if ($isSeller)
            <hr />
            <div id="profession-container">
                <div class="form-group">
                    <label>@lang('main.Profession') <span class="text-danger">*</span></label>
                    <input id="input-professions" class="form-control tagify @error('profession') is-invalid @enderror"
                        name="professions" placeholder='Add professions...' value=""/>

                    @include('user.components.validation-error', ['field' => 'professions'])
                </div>

                <div class="form-group pl-4">
                    <a href="javascript:openProfessionDlg()" class="btn btn-icon btn-success mr-2">
                        <i class="flaticon2-plus"></i>
                    </a> @lang('main.You can enter up to 10 Professions')
                </div>
            </div>
            <hr />

            <!-- enroll professional or institute -->
            <div class="form-group">
                <label>@lang('main.select enrolled type') <span class="text-danger">*</span></label>
                <div class="row radio-inline">
                    @foreach($enroll_types as $enrollType)
                        <div class="col-md-6 mt-2">
                            <label class="radio">
                                <input type="radio" id="enrolledRadio{{ $loop->iteration }}" name="enroll_type" class="custom-control-input" value="{{ $enrollType->id }}"
                                    {{ $curEnrollType == $enrollType->id ? 'checked='.'"'.'checked'.'"' : '' }} />
                                <span></span>@lang('main.' . $enrollType->trans_abbr)
                            </label>
                        </div>
                    @endforeach
                </div>

                @include('user.components.validation-error', ['field' => 'enroll_type'])
            </div>
            <!--./ enroll professional or institute -->

            <div class="order-detail-container {{ ($curEnrollType == EnrollType::NOT_ENROLLED || $curEnrollType == null) ? 'd-none' : '' }}">
                <!-- association -->
                <div class="form-group">
                    <label>@lang('main.EnrollTypeName') <span class="text-danger">*</span></label>
                    <select name="association_id" class="form-control form-control-lg @error('association_id') is-invalid @enderror">
                        <option value="">@lang('main.EnrollTypeNamePlaceholder')</option>
                        @foreach($associations as $association)
                            <option value="{{ $association->id }}" {{ $associationId == $association->id  ? 'selected':''}}>{{ $association->name }}</option>
                        @endforeach
                    </select>

                    @include('user.components.validation-error', ['field' => 'association_id'])
                </div>
                <!--./ association -->

                <div class="row">
                    <!-- city -->
                    <div class="col-md-6 form-group">
                        <label>@lang('main.city_label') <span class="text-danger">*</span></label>
                        <input id="city" class="form-control @error('city') is-invalid @enderror"
                            name="city" placeholder="@lang('main.city_label_placeholder')"
                            value="{{ old('city', $user->detail->city) }}"/>

                        @include('user.components.validation-error', ['field' => 'city'])
                    </div>
                    <!--./ city -->

                    <!-- registration number -->
                    <div class="col-md-6 form-group">
                        <label>@lang('main.Registration Number')</label>@lang('main.is_optional')
                        <input id="reg_number" class="form-control @error('reg_number') is-invalid @enderror"
                            name="reg_number" placeholder="@lang('main.Enter your registration number')"
                            value="{{ old('reg_number', $user->detail->reg_number) }}"/>

                        @include('user.components.validation-error', ['field' => 'reg_number'])
                    </div>
                    <!--./ registration number -->
                </div>
            </div>
        @endif

        <div class="row mt-4">
            <!-- photo -->
            <div class="col-md-6 form-group">
                <div class="image-input image-input-outline" id="kt_image_1">
                    <div class="image-input-wrapper" style="background-image: url(/upload/user/{{ $userPhoto ?: DEFAULT_PHOTO }})"></div>
    
                    <label class="btn btn-xs btn-icon btn-circle btn-white btn-hover-text-primary btn-shadow"
                        data-action="change" data-toggle="tooltip" title="" data-original-title="@lang('main.Profile-photo')">
                        <i id="photo-edit-icon" class="fa fa-pen icon-sm text-muted"></i>
                        <input type="file" accept=".png, .jpg, .jpeg" id="photo-input" />
                        <input type="hidden" name="photo" value="{{ old('photo', $user->detail->photo) }}" id="photo-file-name" />
                    </label>
    
                    @include('user.components.validation-error', ['field' => 'photo', 'custom-control' => true])
                </div>

                <button type="button" class="btn btn-primary" style="margin-top: -35px; margin-left: 10px;"
                    onclick="document.getElementById('photo-edit-icon').click()">@lang('main.Change photo')</button>
            </div>
            <!--./ photo -->

            <!-- hourly rate -->
            <div class="col-xl-6 form-group d-none">
                <label>@lang('main.Hourly Rate')</label> @lang('main.is_optional')
                <input type="text" class="form-control form-control-lg input-decimal" name="hourly_rate" placeholder="" value="{{ old('hourly_rate', $user->hourly_rate) }}" />
            </div>
            <!--./ hourly rate -->
        </div>

        @if ($isSeller)
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
                            @error('description_' . $defaultLang) is-invalid @enderror" minValidLength="5000"
                            cols="120" rows="10">{{ old('description_' . $defaultLang, $user->detail->{'description_' . $defaultLang}) }}</textarea>
                        <div class="counter-status text-right text-muted"><span class="current-count"></span> / <span class="total-count"></span></div>

                        @include('user.components.validation-error', ['field' => 'description_' . $defaultLang])
                    </div>

                    @foreach($supportLocales as $localeCode => $locale)
                        @if ($localeCode != $defaultLang)
                            <div class="tab-pane fade" id="desc-{{ $localeCode }}" role="tabpanel" aria-labelledby="{{ $localeCode }}-desc-tab">
                                <textarea class="form-control profile-desc" name="description_{{ $localeCode }}" minValidLength="5000"
                                    cols="120" rows="10">{{ old('description_' . $localeCode, $user->detail->{'description_' . $localeCode}) }}</textarea>
                                
                                @include('user.components.validation-error', ['field' => 'description_' . $localeCode])
                            </div>
                        @endif
                    @endforeach
                </div>
            </div>
        @endif
    </div>

    <div class="d-flex justify-content-end border-top mt-5 pt-10">
        <button type="submit" class="btn btn-primary font-weight-bold px-9 py-2" name="profile_save" value="profile-save" data-wizard-type="action-save">@lang('main.Save')</button>
    </div>
</form>

{{-- Select profession modal --}}
@include('user.pages.profile.profession-modal')
{{--./ Select profession modal --}}

@section('partial-scripts')
    @include('user.include-plugins.input-tel.input-tel-js')
    @include('user.pages.profile.profile-js')
@endsection
