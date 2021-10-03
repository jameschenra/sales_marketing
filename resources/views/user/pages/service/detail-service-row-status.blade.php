@php
    use App\Models\Service;
    use App\Models\ServiceOffice;
    use App\Enums\ClientPaymentType;
@endphp

<div class="online-status-container">
    
    @if ($service->provide_online_type == Service::PROVIDE_ONLINE_TYPE)
        <span class="label label-xl label-info label-dot"></span>
        <span class="font-weight-bold">@lang('main.Available online')</span>
    @else
        @if(count($service->offices) == 1)
            <span class="label label-xl label-info label-dot"></span>
            <span class="font-weight-bold">
                @php $office = $service->offices[0]; @endphp
                @if ($office->onsite_type == ServiceOffice::TYPE_ONSITE)
                    @lang('main.Available only on-site')
                @elseif ($office->provide_range)
                    @if ($office->onsite_type == ServiceOffice::TYPE_OFFSITE)
                        @lang('main.Available only off-site within a range of') {{ $office->provide_range }}km
                    @else
                        @lang('main.Available on-site and off-site within a range of') {{ $office->provide_range }}km
                    @endif
                @endif
            </span>
        @else
            @php
                $serviceSiteStatus = $service->availableSiteStatus();
            @endphp
            <span class="label label-xl label-info label-dot"></span>
            <span class="font-weight-bold">
                @if ($serviceSiteStatus == ServiceOffice::TYPE_ONSITE)
                    @lang('main.Available only on-site')
                @elseif($serviceSiteStatus == ServiceOffice::TYPE_OFFSITE)
                    @lang('main.Available only off-site')
                @elseif($serviceSiteStatus == ServiceOffice::TYPE_ONOFFSITE)
                    @lang('main.Available on-site and off-site')
                @endif
            </span>
        @endif
    @endif
    
    @if ($service->provide_online_type == Service::PROVIDE_ONLINE_TYPE)
        <span class="label label-xl label-info label-dot ml-2"></span>
        <span class="font-weight-bold">@lang('main.pay_online')</span>
    @else
        <span class="label label-xl label-info label-dot ml-2"></span>
        <span class="font-weight-bold">
            @switch ($service->client_payment_type)
                @case (Service::PAYMENT_TYPE_ONLINE)
                    @if ($service->booking_confirm == Service::BOOKING_DIRECTLY)
                        @lang('main.Direct booking')
                    @else
                        @lang('main.Booking with confirmation')
                    @endif
                    @break
                @case (Service::PAYMENT_TYPE_ONLINEONSITE)
                    @if ($service->booking_confirm == Service::BOOKING_DIRECTLY)
                        @lang('main.Direct booking or with confirmation')
                    @else
                        @lang('main.Booking with confirmation')
                    @endif
                    @break
                @case (Service::PAYMENT_TYPE_ONSITE)
                @case (Service::PAYMENT_TYPE_FREE)
                    @lang('main.Booking with confirmation')
                    @break
            @endswitch
        </span>

        <span class="label label-xl label-info label-dot ml-2"></span>
        <span class="font-weight-bold">
            @switch ($service->client_payment_type)
                @case (Service::PAYMENT_TYPE_ONLINE)
                    @lang('main.pay_online')
                    @break
                @case (Service::PAYMENT_TYPE_ONSITE)
                    @lang('main.pay_on-site')
                    @break
                @case (Service::PAYMENT_TYPE_ONLINEONSITE)
                    @lang('main.pay_online_or_on-site')
                    @break
                @case (Service::PAYMENT_TYPE_FREE)
                    @lang('main.free-service')
                    @break
            @endswitch
        </span>
    @endif
    
</div>