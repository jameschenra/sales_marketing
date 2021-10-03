<!-- Navigation Menu-->
<ul class="navigation-menu mobile-menu-container pt-3">
    <li class="has-submenu">
        <a href="javascript:void(0)">
            <img class="rounded-sm language-img" style="width: 20px; margin-right: 5px;" src="{{ $languageList[0]['icon'] }}" alt=""/> {{ $languageList[0]['label'] }}</a><span class="menu-arrow"></span>
        <ul class="submenu">
            <li><a href="{{ url('locale/' . $languageList[1]['locale']) }}"><img class="rounded-sm" style="width: 20px; margin-right: 5px;" src="{{ $languageList[1]['icon'] }}" alt=""/> {{ $languageList[1]['label'] }}</a></li>
            <li><a href="{{ url('locale/' . $languageList[2]['locale']) }}"><img class="rounded-sm" style="width: 20px; margin-right: 5px;" src="{{ $languageList[2]['icon'] }}" alt=""/> {{ $languageList[2]['label'] }}</a></li>
        </ul>
    </li>

    @guest
        <li><a href="{{ route('user.professionals.howitworks') }}"><button class="btn btn-primary">@lang('main.Provide a Service')</button></a></li>
    @endguest

    @auth
        <li><a href="{{ route('user.service.create') }}"><button class="btn btn-primary">@lang('main.Post Service')</button></a></li>
    @endauth
    
    <li class="has-submenu">
        <a href="javascript:void(0)"><span class="menu-icon">{!! file_get_contents(public_path().'/img/menu_icons/search.svg') !!}</span> @lang('main.Search')</a><span class="menu-arrow"></span>
        <ul class="submenu">
            <li><a href="{{ route('user.professionals.search') }}">@lang('main.Professionals')</a></li>
            <li><a href="{{ route('user.services.search') }}">@lang('main.Services')</a></li>
        </ul>
    </li>

    @auth
        <li class="{{ $profileCompleteMenu }}"><a href="{{ route('user.professionals.detail', [$authUser->slug]) }}">
            <span class="menu-icon">{!! file_get_contents(public_path().'/img/menu_icons/my-page.svg') !!}</span>
            @lang('main.menu.myProfessionalsPage')</a>
        </li>

        <li class="{{ $serviceCompleteMenu }} d-none"><a href="{{ route('user.settings') }}">
            <span class="menu-icon">{!! file_get_contents(public_path().'/img/menu_icons/settings.svg') !!}</span>
            @lang('main.Settings')</a>
        </li>

        <li class="{{ $hasNotify ? 'has-notify' : '' }}"><a href="{{ route('user.settings.notify') }}">
            <i class="menu-icon far fa-bell icon-md"></i> @lang('main.Notifications')</a>
        </li>

        <li><a href="{{ route('user.profile.edit') }}">
            <span class="menu-icon">{!! file_get_contents(public_path().'/img/menu_icons/profile-setting.svg') !!}</span>
            @lang('main.Edit Profile')</a>
        </li>

        <li><a href="{{ route('user.profile.password.show') }}">
            <span class="menu-icon">{!! file_get_contents(public_path().'/img/menu_icons/change-password.svg') !!}</span>
            @lang('main.Change Password')</a>
        </li>

        <li><a href="{{ route('user.book') }}">
            <span class="menu-icon">{!! file_get_contents(public_path().'/img/menu_icons/cart-page.svg') !!}</span>
            @lang('main.My Purchases')</a>
        </li>

        <li><a href="{{ route('user.favourite.index') }}">
            <span class="menu-icon">{!! file_get_contents(public_path().'/img/menu_icons/favorite.svg') !!}</span>
            @lang('main.My favorites')</a>
        </li>

        <li class="d-none"><a href="#">
            <span class="menu-icon">{!! file_get_contents(public_path().'/img/menu_icons/messages.svg') !!}</span>
            @lang('main.Messages')</a>
        </li>

        <li class="{{ $contactCompleteMenu }}"><a href="{{ route('user.office.mylist') }}">
            <span class="menu-icon">{!! file_get_contents(public_path().'/img/menu_icons/my-offices.svg') !!}</span>
            @lang('main.My Offices')</a>
        </li>

        <li class="{{ $contactCompleteMenu }}"><a href="{{ route('user.profile.billing.detail') }}">
            <span class="menu-icon">{!! file_get_contents(public_path().'/img/menu_icons/billing-details.svg') !!}</span>
            @lang('main.Billing details')</a>
        </li>

        <li class="{{ $serviceCompleteMenu }}"><a href="{{ route('user.service.mylist') }}">
            <span class="menu-icon">{!! file_get_contents(public_path().'/img/menu_icons/my-services.svg') !!}</span>
            @lang('main.My Services')</a>
        </li>

        <li class="{{ $serviceCompleteMenu }}"><a href="{{ route('user.orders.index') }}">
            <span class="menu-icon"><span class="menu-icon">{!! file_get_contents(public_path().'/img/menu_icons/customer-orders.svg') !!}</span> @lang('main.Customers Orders')</a>
        </li>

        <li class="{{ $serviceCompleteMenu }}"><a href="{{ route('user.post.mylist') }}">
            <span class="menu-icon"><span class="menu-icon">{!! file_get_contents(public_path().'/img/menu_icons/my-articles.svg') !!}</span> @lang('main.My Articles')</a>
        </li>

        <li class={{ $balanceMenu }}><a href="{{ route('user.balance.show') }}">
            <span class="menu-icon">{!! file_get_contents(public_path().'/img/menu_icons/balance.svg') !!}</span>
            @lang('main.Balance') €{{ number_format($authUser->wallet_balance, 2) }}</a>
        </li>

        <li><a href="javascript:void(0)" data-toggle="modal" data-target="#invite-friend-modal">
            <span class="menu-icon">{!! file_get_contents(public_path().'/img/menu_icons/invite-friend.svg') !!}</span>
            @lang('main.Invite Friend')</a>
        </li>

        <li><a href="{{ route('user.help') }}">
            <span class="menu-icon">{!! file_get_contents(public_path().'/img/menu_icons/help.svg') !!}</span>
            @lang('main.Help')</a>
        </li>

        <li>
            <a href="javascript:void(0)" onclick="document.getElementById('form-signout-mobile').submit();">
                <span class="menu-icon">{!! file_get_contents(public_path().'/img/menu_icons/logout.svg') !!}</span>
                @lang('main.Sign Out')</a>
            <form action="{{ route('user.auth.logout') }}" method="POST" id="form-signout-mobile">
                @csrf
            </form>
        </li>
        <li>
            <a href="javascript:void(0)">&nbsp;</a>
        </li>
        <li>
            <a href="javascript:void(0)">&nbsp;</a>
        </li>
    @endauth

    @guest
        <!-- Login Button -->
        <li><a href="{{ route('user.auth.showLogin') }}">
            <span class="menu-icon">{!! file_get_contents(public_path().'/img/menu_icons/login.svg') !!}</span>
            @lang('main.Login')</a>
        </li>

        <li><a href="{{ route('user.auth.showSignup') }}">
            <span class="menu-icon">{!! file_get_contents(public_path().'/img/menu_icons/register.svg') !!}</span>
            @lang('main.Sign Up')</a>
        </li>
        <!--./ Login Button-->
    @endguest
</ul>
