@php
    use App\Enums\UserType;    
@endphp

{{-- Extends layout --}}
@extends('user.layout.default')

@section('content')
    <section class="bg-content d-flex align-items-center">
        <div class="container">
            <h3 class="mb-10 font-weight-bold text-dark">@lang('main.Notifications')</h3>

            @if ($showNotifyLowBalance)
                <div class="notifications-list">
                    <div class="alert alert-primary alert-dismissible fade show">
                        <button type="button" class="close remove-notification"
                            onclick="removeNotification('showNotifyLowBalance')" data-dismiss="alert">&times;</button>
                        {!! trans('main.Attention your free services can not be displayed') !!}
                    </div>
                </div>
            @elseif($showNotifyHasNoPost)
                @if (auth()->user()->type == UserType::BUYER)
                    <div class="notifications-list">
                        <div class="alert alert-primary alert-dismissible fade show">
                            <button type="button" class="close remove-notification"
                                onclick="removeNotification('showNotifyHasNoPost')" data-dismiss="alert">&times;</button>
                            {!! trans('main.notification to new seller for post service') !!}
                        </div>
                    </div>
                @endif
            @else
                <p class="text-center">{{ trans('main.You have no notification message on page') }}</p>
            @endif
        </div>
    </section>
@endsection

@section('scripts')
    <script>
        function removeNotification(notifyKey) {
            ajax_post("{{ route('user.settings.notify.update') }}",
                {
                    notify_key: notifyKey
                },
                function(result) {
                    if (result.data && result.data.message) {
                        window.location.reload();
                    }
                }
            );
        }
    </script>
@endsection
