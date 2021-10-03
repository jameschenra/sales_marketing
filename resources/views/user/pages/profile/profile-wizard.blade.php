{{-- Extends layout --}}
@extends('user.layout.default')

{{-- Styles Section --}}
@section('styles')
    {{ Html::style(adminAsset('css/pages/wizard/wizard-1.css?v=7.0.5')) }}
    {{ Html::style(userAsset('pages/profile/wizard.css')) }}
@endsection

{{-- Content --}}
@section('content')
<section class="bg-content complete-profile d-flex align-items-center">
    <div class="container">
        <div class="wizard wizard-1" id="kt_wizard_v1" data-wizard-state="step-first" data-wizard-clickable="false">
            {{-- Wizard Body --}}
            @switch($step)
                @case('profile')
                    <div class="my-10 px-8 my-lg-15 px-lg-10">
                        @include("user.pages.profile.profile-form-wizard")
                        @break
                    </div>
                @case('contact')
                    <div class="my-10 px-8 my-lg-15 px-lg-10">
                        @include("user.pages.office.office-form-wizard")
                        @break
                    </div>
                @default
            @endswitch
            {{--./ Wizard Body --}}

        </div>
    </div>
    <!--end container-->
</section>

@endsection


{{-- Scripts Section --}}
@section('scripts')
    
@endsection
