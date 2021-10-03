{{-- Content --}}
@if (config('layout.user.content.extended'))
    @yield('content')
@else
    <div class="content-margin-top @yield('content-class')"></div>

    @if(session('alert'))
        <div class="alert-wrapper container">
            <div class="d-flex justify-content-center">
                <div class="alert alert-{{ session('alert.type', 'success') }} global-error" role="alert">
                    <button type="button" class="close" data-dismiss="alert">&times;</button>
                    {!! session('alert.msg') !!}
                </div>
            </div>
        </div>
    @endif

    <div class="content-container p-0 @yield('content-class')">
        @yield('content')
    </div>
@endif
