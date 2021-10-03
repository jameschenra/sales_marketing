<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" {{ Metronic::printAttrs('html') }} {{ Metronic::printClasses('html') }}>
    <head>
        <meta charset="utf-8"/>

        {{-- Title Section --}}
        <title>{{ $title_sl ?? config('app.name') }}</title>

        {{-- Meta Data --}}
        <meta name="description" content="{{ $description_sl ?? '' }}">
        <meta name="keywords" content="{{ $keyword_sl ?? '' }}">
        <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no, maximum-scale=1, user-scalable=0"/>
        @yield('meta-seo')
        

        {{-- Favicon --}}
        <link rel="shortcut icon" href="{{ imageAsset('app_logos/icon-50x48.svg') }}" />

        {{-- Fonts --}}
        {{ Metronic::getGoogleFontsInclude() }}

        {{-- Global Theme Styles (used by all pages) --}}
        @foreach(config('layout.user.resources.css') as $style)
            <link href="{{ adminAsset($style) }}" rel="stylesheet" type="text/css"/>
        @endforeach

        <!-- Icons for Landrick -->
        {{ Html::style(userAsset('css/materialdesignicons.min.css')) }}
        {{ Html::style('https://unicons.iconscout.com/release/v2.1.7/css/unicons.css') }}

        <link href="{{ adminAsset('css/app.css') }}" rel="stylesheet" type="text/css"/>

        {{-- Custom Styles for User Side --}}
        <link href="{{ userAsset('menu.css') }}" rel="stylesheet" type="text/css"/>
        <link href="{{ userAsset('style.css') }}" rel="stylesheet" type="text/css"/>
        <link href="{{ userAsset('spacing.css') }}" rel="stylesheet" type="text/css"/>

        {{-- Custom partial CSS --}}
        @yield('styles')
        @yield('partial-styles')
    </head>

    <body class="subheader-enabled">

        <div id="preloader">
            <div id="status">
                <div class="spinner">
                </div>
            </div>
        </div>

        @include('user.layout.partials._page-loader')

        @include('user.layout.base._layout')

        @include('user.components.ajax-error')

        @include('user.components.invite-friend')

        <!-- Language Modal -->
        @if(session()->get('language_modified'))
            @include('user.components.language-update-notify')
        @endif
        <!--./ Language Modal -->

        @include('cookieConsent::index')

        {{-- Global Config (global config for global JS scripts) --}}
        <script>
            var KTAppSettings = {!! json_encode(config('layout.user.js'), JSON_PRETTY_PRINT|JSON_UNESCAPED_SLASHES) !!};
            var APP_MODE = '{{ env("APP_MODE", "DEV") }}';
        </script>

        {{-- Global Theme JS Bundle (used by all pages)  --}}
        @foreach(config('layout.user.resources.js') as $script)
            <script src="{{ adminAsset($script) }}" type="text/javascript"></script>
        @endforeach

        {{ Html::script(adminAsset('js/app.js')) }}
        {{ Html::script(userAsset('js/jquery.easing.min.js')) }}
        
        {{ Html::script(userAsset('common-js/ajax.js')) }}
        {{ Html::script(userAsset('common-js/validation.js')) }}
        {{ Html::script(userAsset('common-js/common.js')) }}
        {{ Html::script(userAsset('js/feather.min.js')) }}
        {{ Html::script(userAsset('js/app.js')) }}

        <script>
            $(function(){
                $('#invite-submit-btn').on('click', onSubmitInviteFriend);
                if ($("#language-modal")) {
                    $("#language-modal").modal("show");
                }
            });
            function onSubmitInviteFriend(event) {
                $('#invite-section #success_msg').hide();
                $('#invite-section #error_msg').hide();

                var email = $('#invite-firend-email').val();
                var name = $('#invite-friend-name').val();
                var description = $('#description').val();
                var filter = /^([a-zA-Z0-9_\.\-])+\@(([a-zA-Z0-9\-])+\.)+([a-zA-Z0-9]{2,4})+$/;
                if(!filter.test(email) || email == "")
                {
                    $('#invite-section #error_msg').html("<div class='text-center'><i class='fa fa-times'> {{ trans('main.valid email') }} </i></div>").show();
                    return false;
                }
                $('#invite-section #error_msg').hide();

                ajax_post('/invite',
                    {
                        email: email,
                        name: name,
                        description: description
                    },
                    function (result) {
                        $('#invite-friend-email, #description', '#invite-friend-name').val("");
                        $('#invite-section #success_msg').html("<div class='text-center'><i class='fa fa-check'></i> {{ trans('main.Invite sent message') }}</div>").show();
                    }
                );
            }
        </script>
        {{-- Includable JS --}}
        @yield('scripts')
        @yield('partial-scripts')
    </body>
</html>

