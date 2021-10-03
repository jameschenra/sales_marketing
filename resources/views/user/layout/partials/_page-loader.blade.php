{{-- Page Loader Types --}}

{{-- Default --}}
@if (config('layout.user.page-loader.type') == 'default')
    <div class="page-loader">
        <div class="spinner spinner-primary"></div>
    </div>
@endif

<div id="ajax-loader" class="page-loader d-none" style="justify-content: center; align-items:center; opacity: 0.8; z-index: 2000;">
    <div class="spinner spinner-primary"></div>
</div>

{{-- Spinner Message --}}
@if (config('layout.user.page-loader.type') == 'spinner-message')
    <div class="page-loader page-loader-base">
        <div class="blockui">
            <span>Please wait...</span>
            <span><div class="spinner spinner-primary"></div></span>
        </div>
    </div>
@endif

{{-- Spinner Logo --}}
@if (config('layout.user.page-loader.type') == 'spinner-logo')
    <div class="page-loader page-loader-logo">
        <img alt="{{ config('app.name') }}" src="{{ asset('media/logos/logo-letter-1.png') }}"/>
        <div class="spinner spinner-primary"></div>
    </div>
@endif
