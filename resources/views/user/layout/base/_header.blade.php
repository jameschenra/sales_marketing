@php
    use App\Enums\UserType;
    use App\Models\UserDetail;

    if (auth()->check()) {
        $authUser = auth()->user();

        if ($authUser->type == UserType::SELLER) {
            $hasNotify = hasNotify();

            $isProfileCompleted = ($authUser->detail->profile_wizard_completed >= UserDetail::PROFILE_COMPLETED);
            $isContactCompleted = ($authUser->detail->profile_wizard_completed >= UserDetail::CONTACT_COMPLETED);
            $isServiceCompleted = ($authUser->detail->profile_wizard_completed >= UserDetail::SERVICE_COMPLETED);

            $profileCompleteMenu = (!$isProfileCompleted || !$isServiceCompleted) ? 'd-none' : '';
            $contactCompleteMenu = !$isContactCompleted ? 'd-none' : '';
            $serviceCompleteMenu = !$isServiceCompleted ? 'd-none' : '';
            $balanceMenu = ($authUser->hasPurchase() || $isServiceCompleted) ? '' : 'd-none';
        } else {
            $hasNotify = false;
            $profileCompleteMenu = 'd-none';
            $contactCompleteMenu = 'd-none';
            $serviceCompleteMenu = 'd-none';
            $balanceMenu = $authUser->hasPurchase() ? '' : 'd-none';
        }
    }
@endphp

{{-- Header --}}
<header id="topnav" class="defaultscroll sticky @yield('header-bg')">
    <div class="container">
        <!-- Logo container-->
        <div>
            <a class="logo" href="/">
                <img class="logo-black-color" src="{{ imageAsset('app_logos/logo-534x78.svg') }}" height="35" alt="">
                <img class="logo-white-color" src="{{ imageAsset('app_logos/blue-white.svg') }}" height="35" alt="">
            </a>
        </div>
        <!--./ Logo container-->

        {{-- desktop menu --}}
        <div class="desktop-menu">
            <div class="buy-button">
                <div class="dropdown">
                    <div class="topbar-item" data-toggle="dropdown" data-offset="10px,0px">
                        <div class="btn btn-icon btn-clean btn-dropdown btn-lg mr-1">
                            <img class="h-20px w-20px rounded-sm" src="{{ $languageList[0]['icon'] }}" alt=""/>
                        </div>
                    </div>
    
                    <div class="dropdown-menu p-0 m-0 dropdown-menu-anim-up dropdown-menu-sm dropdown-menu-right" style="line-height: 16px;">
                        @include('admin.layout.partials.extras.dropdown._languages')
                    </div>
                </div>
            </div>
    
            <!-- Login Button -->
            @guest
                <div class="buy-button">
                    <a href="{{ route('user.auth.showLogin') }}">@lang('main.Login')</a>
                </div>
                <div class="buy-button">
                    <a href="{{ route('user.auth.showSignup') }}">@lang('main.Sign Up')</a>
                </div>
            @endguest
            <!-- Login Button-->
        </div>
        

        <!-- Mobile menu toggle-->
        <div class="menu-extras">
            <div class="menu-item">
                <a class="navbar-toggle">
                    <div class="lines">
                        <span></span>
                        <span></span>
                        <span></span>
                    </div>
                </a>
            </div>
        </div>
        <!--./ Mobile menu toggle-->

        <div id="navigation">
            @include('user.layout.base._menu-desktop')

            @include('user.layout.base._menu-mobile')
        </div>
        <!--end navigation-->
    </div>
    <!--end container-->
</header>