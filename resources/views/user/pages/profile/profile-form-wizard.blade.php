@php
    use App\Enums\UserType;

    $user = auth()->user();
    $isSeller = ($user->type == UserType::SELLER);
    $defaultLang = $user->default_language ?: app()->getLocale();

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

    <input type="hidden" name="mode" value="profile-wizard" />
    <div class="pb-5" data-wizard-type="step-content" data-wizard-state="current">
        <h3 class="mb-10 font-weight-bold text-dark">@lang('main.Edit Profile')</h3>
        <div class="row">
            <div class="col-md-3">
                <div class="bs-vertical-wizard">
                    <ul>
                        <li class="current">
                            <a class="wizard-step-link" href="javascript:void(0)" data-step="1">@lang('main.Step 1') <i class="ico fa fa-check ico-white d-none"></i>
                                <span class="wizard-step-number">1</span>
                                <span class="desc">@lang('main.Enter country, languages spoken and profession')</span><br />
                            </a>
                        </li>
                        <li class="">
                            <a class="wizard-step-link" href="javascript:void(0)" data-step="2">@lang('main.Step 2') <i class="ico fa fa-check ico-white d-none"></i>
                                <span class="wizard-step-number">2</span>
                                <span class="desc">@lang('main.Enrollment')</span><br />
                            </a>
                        </li>
                        <li class="">
                            <a class="wizard-step-link" href="javascript:void(0)" data-step="3">@lang('main.Step 3') <i class="ico fa fa-check ico-white d-none"></i>
                                <span class="wizard-step-number">3</span>
                                <span class="desc">@lang('main.Description about you')</span><br />
                            </a>
                        </li>
                        <li>
                            <a class="wizard-step-link" href="javascript:void(0)" data-step="4">@lang('main.Step 4') <i class="ico fa fa-check ico-white d-none"></i>
                                <span class="wizard-step-number">4</span>
                                <span class="desc">@lang('main.Image')</span><br />
                            </a>
                        </li>
                    </ul>
                </div>

                <div class="bs-horizontal-wizard">
                    <ul class="multistep-progressbar">                        
                        <li class="step-container current-step">
                            <span class="step-icon"><i class="fa fa-check"></i></span>
                            <span class="step-title">Step 1</span>
                        </li>
                        <li class="step-container">
                            <span class="step-icon"><i class="fa fa-check"></i></span>
                            <span class="step-title">Step 2</span>
                        </li>
                        <li class="step-container">
                            <span class="step-icon"><i class="fa fa-check"></i></span>
                            <span class="step-title">Step 3</span>
                        </li>
                        <li class="step-container">
                            <span class="step-icon"><i class="fa fa-check"></i></span>
                            <span class="step-title">Step 4</span>
                        </li>
                    </ul>
                </div>
            </div>
            
            <div class="col-md-9">
                <div id="profile-form-step1">
                    @include('user.pages.profile.profile-form-step1')
                </div>

                <div id="profile-form-step2" style="display: none;">
                    @include('user.pages.profile.profile-form-step2')
                </div>

                <div id="profile-form-step3" style="display: none;">
                    @include('user.pages.profile.profile-form-step3')
                </div>

                <div id="profile-form-step4" style="display: none;">
                    @include('user.pages.profile.profile-form-step4')
                </div>
            </div>
        </div>
    </div>

    <!--begin::Wizard Actions-->
    <div class="profile-navigation-container border-top">
        <div class="mr-2">
            <button id="previous-profile-step" type="button" class="btn btn-light-primary font-weight-bold w-100px"
                onclick="onPreviousProfileStep()" style="display: none">@lang('main.Previous')</button>
        </div>
        <div>
            <input type="hidden" name="save_next" value="1" />
            <input type="hidden" name="profile_save" value="profile-wizard" />
            <button id="next-profile-step" type="button" onclick="onNextProfileStep()"
                class="btn btn-primary font-weight-bold 100px px-9">@lang('main.Next')</button>
            <button id="complete-profile-step" type="submit" onclick="onSubmitForm(event)"
                class="btn btn-primary font-weight-bold 100px px-9" style="display: none">@lang('main.Next')</button>
        </div>
    </div>
    <!--end::Wizard Actions-->
</form>

<!-- Modal-->
<div class="modal fade" id="welcome-modal" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title w-100 text-center">@lang('main.welcome.seller.title')</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <i aria-hidden="true" class="ki ki-close"></i>
                </button>
            </div>
            <div class="modal-body">
                @lang('main.welcome.seller')
            </div>
            <div class="modal-footer justify-content-sm-between justify-content-center">
                <a href="{{ route('home') }}" class="btn btn-light-primary font-weight-bold">@lang('main.iwill.continue.later')</a>
                <button type="button" class="btn btn-primary font-weight-bold" data-dismiss="modal">@lang('main.yes.continue.complete-profile')</button>
            </div>
        </div>
    </div>
</div>

{{-- Select profession modal --}}
@include('user.pages.profile.profession-modal')
{{--./ Select profession modal --}}

@section('partial-scripts')
    @include('user.include-plugins.input-tel.input-tel-js')
    @include('user.pages.profile.profile-js')
@endsection
