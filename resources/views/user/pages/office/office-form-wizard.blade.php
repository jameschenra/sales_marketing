@php
    if ($office) {
        $officePhoneNumber = old('phone_number', $office->phone_number);
        $officeCountryId = old('country_id', $office->country_id);
        $hasCalendar = old('has_calendar', $office->has_calendar);
    } else {
        $user = auth()->user();
        $officePhoneNumber = old('phone_number', $user->phone);
        $officeCountryId = old('country_id', $user->detail->country_id);
        $hasCalendar = old('has_calendar');
    }
@endphp

@section('partial-styles')
    <style>
        #map {
            min-height: 400px;
        }

        .office-form-step {
            display: none;
        }
    </style>
    @include('user.include-plugins.input-tel.input-tel-css')
    {{ Html::style(userAsset('libraries/multidatepicker/css/mdp.css')) }}
    {{ Html::style(userAsset('libraries/multidatepicker/css/pepper-ginder-custom.css')) }}
    {{ Html::style(userAsset('pages/service/step-wizard.css')) }}
@endsection

<form class="form" method="POST" action="{{ route('user.office.store') }}" id="form-office">
    @include('user.components.validation-top-error')

    @csrf
    <input type="hidden" name="office_id" value="{{ $office ? $office->id : null }}" />

    <div class="pb-5" data-wizard-type="step-content" data-wizard-state="current">
        <h3 class="mb-10 font-weight-bold text-dark">@lang('main.Enter Office')</h3>
        <div class="row">

            <div class="col-md-3">
                {{-- vertical step wizard --}}
                <div class="bs-vertical-wizard">
                    <ul>
                        <li class="current">
                            <a class="wizard-step-link" href="javascript:void(0)" data-step="1">@lang('main.Step 1') <i class="ico fa fa-check ico-white d-none"></i>
                                <span class="wizard-step-number">1</span>
                                <span class="desc">@lang('main.Enter Office name, telephone')</span><br />
                            </a>
                        </li>

                        <li class="">
                            <a class="wizard-step-link" href="javascript:void(0)" data-step="2">@lang('main.Step 2') <i class="ico fa fa-check ico-white d-none"></i>
                                <span class="wizard-step-number">2</span>
                                <span class="desc">@lang('main.Country, Address')</span><br />
                            </a>
                        </li>
                        <li class="">
                            <a class="wizard-step-link" href="javascript:void(0)" data-step="3">@lang('main.Step 3') <i class="ico fa fa-check ico-white d-none"></i>
                                <span class="wizard-step-number">3</span>
                                <span class="desc">@lang('main.Timetable')</span><br />
                            </a>
                        </li>
                        <li>
                            <a class="wizard-step-link" href="javascript:void(0)" data-step="4">@lang('main.Step 4') <i class="ico fa fa-check ico-white d-none"></i>
                                <span class="wizard-step-number">4</span>
                                <span class="desc">@lang('main.Calendar')</span><br />
                            </a>
                        </li>
                        <li>
                            <a class="wizard-step-link" href="javascript:void(0)" data-step="5">@lang('main.Step 5') <i class="ico fa fa-check ico-white d-none"></i>
                                <span class="wizard-step-number">5</span>
                                <span class="desc">@lang('main.Billing Detail')</span><br />
                            </a>
                        </li>
                    </ul>
                </div>
                {{-- ./vertical step wizard --}}

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
                        <li class="step-container">
                            <span class="step-icon"><i class="fa fa-check"></i></span>
                            <span class="step-title">Step 5</span>
                        </li>
                    </ul>
                </div>
            </div>
            


            <div class="col-md-9">
                <div id="office-form-step1">
                    @include('user.pages.office.office-form-step1')
                </div>

                <div id="office-form-step2" class="office-form-step">
                    @include('user.pages.office.office-form-step2')
                </div>

                <div id="office-form-step3" class="office-form-step">
                    @include('user.pages.office.office-form-step3')
                </div>

                <div id="office-form-step4" class="office-form-step">
                    @include('user.pages.office.office-form-step4')
                </div>

                <div id="office-form-step5" class="office-form-step">
                    @include('user.pages.office.office-form-step5')
                </div>
            </div>
        </div>

        <input type="hidden" name="mode" value="{{ $mode }}" />

        <!--begin::Wizard Actions-->
        <div class="profile-navigation-container border-top mt-5 pt-10">
            <div class="mr-2">
                <a id="previous-to-profile" href="{{ route('user.profile.wizard', 'profile') }}" class="btn btn-light-primary font-weight-bold">@lang('main.Previous')</a>
                <button id="previous-office-step" type="button" class="btn btn-light-primary font-weight-bold"
                    onclick="onPreviousOfficeStep()" style="display: none">@lang('main.Previous')</button>
            </div>
            <div>
                <input type="hidden" name="save_next" value="1" />
                <button id="next-office-step" type="button" onclick="onNextOfficeStep()"
                    class="btn btn-primary font-weight-bold px-9">@lang('main.Next')</button>
                <button id="complete-office-step" type="submit" onclick="formSubmit(event)"
                    class="btn btn-primary font-weight-bold" style="display: none">@lang('main.Complete')</button>
            </div>
        </div>
        <!--end::Wizard Actions-->
    </div>
</form>

@section('partial-scripts')
    <script>
        var is_office_wizard = true;
    </script>
    {{ Html::script(userAsset('libraries/multidatepicker/jquery-ui-1.12.1.js')) }}
    {{ Html::script(userAsset('libraries/multidatepicker/jquery-ui.multidatespicker.js')) }}
    <script src="https://maps.googleapis.com/maps/api/js?key={{env('MAP_API')}}" type="text/javascript"></script>
    @include('user.include-plugins.input-tel.input-tel-js')
    @include('user.pages.office.office-js')
    @include('user.pages.profile.billing-js')
@endsection