@php
    use App\Models\Service;
@endphp

<div class="step-payment-detail step-form" style="display: none">
    <h3 class="booking-section__title">
        @if($is_online)
            @lang('main.Payment Detail online')
        @else
            @lang('main.Payment Detail offline')
        @endif
    </h3>
    <div class="booking-section__detail">
        <div class="payment-subtotal">
            <p class="detail-info-title">
                @if ($service->client_payment_type == Service::PAYMENT_TYPE_ONLINEONSITE)
                    @lang('main.Total price if pay on-site'):
                @else
                    @lang('main.Total price'):
                @endif
            </p>
            <div class="value"></div>
        </div>
        
        <div class="payment-discount">
            <p class="detail-info-title">
                @if ($service->client_payment_type == Service::PAYMENT_TYPE_ONLINEONSITE)
                    @lang('main.Discount if pay online'):
                @else
                    @lang('main.Discount'):
                @endif
                
            </p>
            <div class="value"></div>
        </div>

        <div class="payment-total">
            <p class="detail-info-title">
                @if ($service->client_payment_type == Service::PAYMENT_TYPE_ONSITE)
                    @lang('main.Total price to pay on-site'):
                @elseif($service->client_payment_type == Service::PAYMENT_TYPE_ONLINEONSITE)
                    @lang('main.Total price if pay online'):
                @elseif($service->client_payment_type == Service::PAYMENT_TYPE_FREE)
                    @lang('main.Total price'): @lang('main.Free Service')
                @else
                    @lang('main.Total price to pay'):
                @endif
            </p>
            <div class="value"></div>
        </div>
        
        {{-- Pay buttons --}}
        <div class="text-center paid-buttons mt-5 mb-10" style="visibility: hidden;">
            <div class="paid-buttons-wrap">
                @if ($service->active)
                    @if ($is_online)
                        <button type="button" id="btn-pay-online" class="btn btn-primary btn-book">{{ trans('main.online.payonline.is.default') }} (<span class="btn-price"></span>)</button>
                    @endif

                    @if ($is_offline)
                        @if ($service->price > 0)                        
                            @if ($service->client_payment_type == Service::PAYMENT_TYPE_ONLINE)
                                @if ($service->booking_confirm == Service::BOOKING_DIRECTLY)
                                    <div><button type="button" id="btn-pay-offline" class="btn btn-primary btn-book">{{ trans('main.offline.payonline.book.directly') }} (<span class="btn-price"></span>)</button></div>
                                @else
                                    <div><button type="button" id="btn-pay-offline" class="btn btn-primary btn-book">{{ trans('main.offline.payonline.book.with.confirmation') }} (<span class="btn-price"></span>)</button></div>
                                @endif
                            @elseif ($service->client_payment_type == Service::PAYMENT_TYPE_ONSITE)
                                <div><button type="button" id="btn-pay-office" class="btn btn-primary mb-5 btn-book">{{ trans('main.offline.payonsite.book.with.confirmation.is.default') }} (<span class="btn-price"></span>)</button></div>
                            @elseif ($service->client_payment_type == Service::PAYMENT_TYPE_ONLINEONSITE)
                                <div><button type="button" id="btn-pay-office" class="btn btn-primary mb-5 btn-book">{{ trans('main.offline.payonsite.book.with.confirmation.is.default') }} (<span class="btn-price"></span>)</button></div>
                                @if ($service->booking_confirm == Service::BOOKING_DIRECTLY)
                                    <div><button type="button" id="btn-pay-offline" class="btn btn-primary btn-book">{{ trans('main.offline.payonline.book.directly') }} (<span class="btn-price"></span>)</button></div>
                                @else
                                    <div><button type="button" id="btn-pay-offline" class="btn btn-primary btn-book">{{ trans('main.offline.payonline.book.with.confirmation') }} (<span class="btn-price"></span>)</button></div>
                                @endif
                            @endif
                        @else   {{-- free service --}}
                            <button type="button" id="btn-pay-free" class="btn btn-primary btn-book">{{ trans('main.offline.is.free.with.confirmation.is.default') }}</button>
                        @endif
                    @endif
                @else
                    <span>{{trans('main.Not currently available')}}</span>
                @endif
            </div>
        </div>
        {{--./ Pay buttons --}}

        <form id="form-payment-terms">
            <div class="form-check">
                <input class="form-check-input" type="checkbox" value="" id="accept-payment-check" required />
                <label class="form-check-label" for="accept-payment-check" style="font-size: 14px;">
                    &nbsp;&nbsp;{!! $is_online ? trans('main.Check box is online') : trans('main.Check box is offline') !!}
                </label>
            </div>
        </form>

        <br />

        <p class="font-weight-normal" style="font-size: 14px;">
            @if ($is_online)
                @lang('main.text.online.payonline.is.default', ['seller_name' => $service->user->name])
            @endif

            @if ($is_offline)
                @if ($service->price > 0)
                    @if ($service->client_payment_type == Service::PAYMENT_TYPE_ONLINE)
                        @if ($service->booking_confirm == Service::BOOKING_DIRECTLY)
                            @lang('main.text.offline.payonline.book.directly', ['seller_name' => $service->user->name])
                        @else
                            @lang('main.text.offline.payonline.book.with.confirmation', ['seller_name' => $service->user->name])
                        @endif
                    @elseif ($service->client_payment_type == Service::PAYMENT_TYPE_ONSITE)
                        @lang('main.text.offline.payonline.with.confirmation.or.onsite.with.confirmation', ['seller_name' => $service->user->name])
                    @elseif($service->client_payment_type == Service::PAYMENT_TYPE_ONLINEONSITE)
                        @lang('main.text.offline.payonline.book.directly.or.onsite.with.confirmation', ['seller_name' => $service->user->name])
                    @endif
                @else   {{-- free service --}}
                    @lang('main.text.offline.is.free.with.confirmation.is.default', ['seller_name' => $service->user->name])
                @endif
            @endif
        </p>
    </div>
</div>