@php
    use App\Models\Service;
@endphp
@if ($service->provide_online_type == Service::PROVIDE_ONLINE_TYPE)
    <div class="mb-2">
        <span class="label label-xl label-info label-dot"></span>
        @lang('main.Delivery in') {{ trans_choice('main.times.day', $service->online_delivery_time, ['d' => $service->online_delivery_time]) }}
        &nbsp; <span class="label label-xl label-info label-dot"></span>
        {{ trans_choice('main.revision', $service->online_revision, ['r' => $service->online_revision]) }} 
        {{ trans_choice('main.revision_include', $service->online_revision) }} 
    </div>
@else
    <div class="mb-2">
        <span class="far fa-clock"></span> {{ $service->prettyDuration }}

        @if($service->hasConsecutively())
            <div class="d-inline-block ml-2">
                <span class="label label-xl label-info label-dot"></span>
                @lang('main.Possibility to book for more time')
            </div>
        @endif
    </div>
@endif