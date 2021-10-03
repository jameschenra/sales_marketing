<!-- Navigation Menu-->
<ul class="navigation-menu desktop-menu">
    <li class="has-submenu">
        <a href="javascript:void(0)">@lang('main.Search')</a><span class="menu-arrow"></span>
        <ul class="submenu">
            <li><a href="{{ route('user.professionals.search') }}">@lang('main.Professionals')</a></li>
            <li><a href="{{ route('user.services.search') }}">@lang('main.Services')</a></li>
        </ul>
    </li>
    @guest
        <li><a href="{{ route('user.professionals.howitworks') }}" class="menu-button"><button class="btn btn-primary btn-responsive">@lang('main.Provide a Service')</button></a></li>
    @endguest
    @auth
    <li><a href="{{ route('user.service.create') }}" class="menu-button"><button class="btn btn-primary btn-responsive">@lang('main.Post Service')</button></a></li>
    @endauth
    
    @auth
        <li class="pr-0 mr-0 {{ $hasNotify ? 'has-notify' : '' }}">
            <a href="{{ route('user.settings.notify') }}" class="pr-0"><i class="menu-icon far fa-bell icon-md" style="min-width: 0"></i></a>
        </li>

        <li class="has-submenu">
            <a href="javascript:void(0)" style="text-transform: capitalize;"><i class="far fa-user icon-md"></i> {{ $authUser->name }}</a><span class="menu-arrow"></span>
            <ul class="submenu user-menu">
                <li class="{{ $profileCompleteMenu }}"><a href="{{ route('user.professionals.detail', [$authUser->slug]) }}" target="_blank">
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

                <li class="has-submenu {{ $contactCompleteMenu }}">
                    <a href="javascript:void(0)">
                        <span class="menu-icon">{!! file_get_contents(public_path().'/img/menu_icons/my-offices.svg') !!}</span>
                        @lang('main.My Offices')
                    </a>
                    <span class="submenu-arrow"></span>

                    <ul class="submenu">
                        <li><a href="{{ route('user.office.mylist') }}">@lang('main.My Offices')</a></li>
                        <li><a href="{{ route('user.office.create') }}">@lang('main.Enter Office')</a></li>
                    </ul>
                </li>

                <li class="{{ $contactCompleteMenu }}"><a href="{{ route('user.profile.billing.detail') }}">
                    <span class="menu-icon">{!! file_get_contents(public_path().'/img/menu_icons/billing-details.svg') !!}</span>
                    @lang('main.Billing details')</a>
                </li>

                <li class="has-submenu {{ $serviceCompleteMenu }}">
                    <a href="javascript:void(0)">
                        <span class="menu-icon">{!! file_get_contents(public_path().'/img/menu_icons/my-services.svg') !!}</span>
                        @lang('main.My Services')
                    </a>
                    <span class="submenu-arrow"></span>

                    <ul class="submenu">
                        <li><a href="{{ route('user.service.mylist') }}">@lang('main.My Services')</a></li>
                        <li><a href="{{ route('user.service.create') }}">@lang('main.Create Service')</a></li>
                    </ul>
                </li>

                <li class="{{ $serviceCompleteMenu }}"><a href="{{ route('user.orders.index') }}">
                    <span class="menu-icon">{!! file_get_contents(public_path().'/img/menu_icons/customer-orders.svg') !!}</span>
                    @lang('main.Customers Orders')</a>
                </li>

                <li class="has-submenu {{ $serviceCompleteMenu }}">
                    <a href="javascript:void(0)">
                        <span class="menu-icon">{!! file_get_contents(public_path().'/img/menu_icons/my-articles.svg') !!}</span>
                        @lang('main.My Articles')
                    </a>
                    <span class="submenu-arrow"></span>

                    <ul class="submenu">
                        <li><a href="{{ route('user.post.mylist') }}">@lang('main.My Articles')</a></li>
                        <li><a href="{{ route('user.post.create') }}">@lang('main.Post Article')</a></li>
                    </ul>
                </li>

                <li class={{ $balanceMenu }}>
                    <a href="{{ route('user.balance.show') }}">
                        <span class="menu-icon">{!! file_get_contents(public_path().'/img/menu_icons/balance.svg') !!}</span>
                        @lang('main.Balance') €{{ number_format($authUser->wallet_balance, 2) }}
                    </a>
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
                    <form action="{{ route('user.auth.logout') }}" method="POST" id="form-signout-desktop">
                        @csrf

                        <a href="javascript:void(0)" onclick="document.getElementById('form-signout-desktop').submit();">
                            <span class="menu-icon">{!! file_get_contents(public_path().'/img/menu_icons/logout.svg') !!}</span>
                            @lang('main.Sign Out')</a>
                    </form>
                </li>
            </ul>
        </li>
    @endauth
    <li></li>
</ul>