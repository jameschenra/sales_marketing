@php
    use App\Models\Service;
    use App\Models\Book;
@endphp

{{-- Extends layout --}}
@extends('user.layout.default')

@section('styles')
    {{ Html::style(userAsset('pages/order/order.css')) }}
    {{ Html::style(userAsset('pages/service/booking.css')) }}
@endsection

@section('content')
<section class="bg-content d-flex align-items-center">
    <div class="container order-view-wrapper">
        <h3 class="mb-10 font-weight-bold text-dark">
            @lang('main.Order details of') <span class="font-weight-bolder">{{ $book->user->full_name }}</span></h3>

        <div class="row">
            {{-- left column --}}
            <div class="col-md-8">
                <div class="mb-5">
                    <p class="mb-5"><span class="font-weight-bolder">{{ trans('main.Service') }}:</span> {{ $book->service->name }}</p>
                    <p><span class="font-weight-bolder">{{ trans('main.Services ordered') }}:</span> {{ $book->number_of_booking }}</p>
                    @if ($book->provide_online_type == Service::PROVIDE_ONLINE_TYPE)
                        <p><span class="font-weight-bolder">{{ trans('main.Delivery date') }}:</span> {{date('d/m/Y', strtotime($book->delivery_date))}}
                        <p><span class="font-weight-bolder">{{ trans('main.Working days') }}:</span> {{ $book->service->online_delivery_time }}</p>
                        <p><span class="font-weight-bolder">{{ trans('main.Numbers-of-revisions-included') }}:</span> {{ $book->online_revision == -1 ? trans('main.Unlimited revisions') : $book->online_revision }}</p>
                    @else
                        <p><span class="font-weight-bolder">{{ trans('main.Booking Date') }}:</span> {{date('d/m/Y', strtotime($book->book_date))}}
                        <span class="font-weight-bolder">{{ trans('main.at') }}</span> {{date('H:i', strtotime($book->book_date))}}</p>
                        <p><span class="font-weight-bolder">{{ trans('main.Duration') }}:</span> {{ $book->prettyDuration }}</p>
                    @endif
                    <p><span class="font-weight-bolder">{{ trans('main.Order no') }}</span> {{ $book->id }}
                    <span class="font-weight-bolder">{{ trans('main.on') }}</span> {{ date('d/m/Y', strtotime($book->created_at)) }}
                    <span class="font-weight-bolder">{{ trans('main.at') }}</span> {{ date('H:i', strtotime($book->created_at)) }}</p>
                </div>

                <div class="mb-4">
                    <p>
                    @switch($book->paid_type)
                        @case(Book::PAID_PAYPAL)
                            <span class="font-weight-bolder">{{ trans('main.Price paid paypal') }}:</span> € {{ number_format($book->total_amount, 2) }}
                            @break

                        @case(Book::PAID_CREDIT)
                            <span class="font-weight-bolder">{{ trans('main.Price paid balance') }}:</span> € {{ number_format($book->total_amount, 2) }}
                            @break

                        @case(Book::PAID_OFFICE)
                            <span class="font-weight-bolder">{{ trans('main.Price paid office') }}:</span> € {{ number_format($book->total_amount, 2) }}
                            @break

                        @case(Book::PAID_FREE)
                            {!! trans('main.Price paid free') !!}
                            @break
                    @endswitch
                    </p>
                    <p>
                        @if(isset($book->clientTransaction))
                            <span class="font-weight-bolder">{{ trans('main.Payment transaction No') }}</span> {{ $book->clientTransaction->id }}
                        @endif
                    </p>
                    <p>
                        <span class="font-weight-bolder">{{ trans('main.Fee') }}:</span>
                        € {{ number_format($book->total_fee, 2) }}
                    </p>
                    <p>
                        <span class="font-weight-bolder">{{ trans('main.Transaction fee No') }}</span>
                        {{ (isset($book->feeTransaction)) ? $book->feeTransaction->id : '___' }}
                    </p>
                </div>

                <div class="mb-5">
                    <p><span class="font-weight-bolder">{{ trans('main.Location to provide service buyer') }}:</span> 
                        @if ($book->provide_online_type == Service::PROVIDE_ONLINE_TYPE)
                            {{ $book->office->name }}
                        @else
                            @if($book->user_address)
                                @lang('main.Customers Address')
                            @else
                                {{ $book->office->name }}
                            @endif
                        @endif
                    </p>

                    <p><span class="font-weight-bolder">{{ trans('main.Address') }}:</span> 
                        @if ($book->user_address)
                            @if ($book->status == Book::STATUS_WAIT_CONFIRM)
                                @lang('main.Address will be displayed after confirmation')
                            @else
                                {{ $book->user_address }}
                            @endif
                        @else
                            {{ $book->office->full_address }}
                        @endif
                    </p>
                </div>

                @if ($book->provide_online_type != Service::PROVIDE_ONLINE_TYPE && $book->status != Book::STATUS_CANCEL)
                    @if($book->status == Book::STATUS_WAIT_CONFIRM)
                        <div class="text-muted">{{ trans("main.After confirmation you'll receive contact details of", ['seller_name' => $book->user->name]) }}</div>
                    @else
                        <div class="mb-5">
                            <p><span class="font-weight-bolder">{{ trans('main.Contact details of') }}:</span> {{ $book->user->full_name }}</p>
                            <p><span class="font-weight-bolder">{{ trans('main.Phone') }}:</span> {{ $book->user->phone }}</p>
                            <p><span class="font-weight-bolder">{{ trans('main.Email') }}:</span> {{ $book->user->email }}</p>
                        </div>
                    @endif
                @endif

                @if ($book->provide_online_type == Service::PROVIDE_OFFLINE_TYPE && !empty($book->message))
                    <div class="mb-2">
                    <p>
                        <span class="font-weight-bolder">{{ trans('main.Message sent during the reservation') }}:</span>
                        <br>
                        {{ $book->message }}
                    </p>
                    </div>
                @endif
            </div>
            {{-- ./left column --}}

            {{-- right column --}}
            <div class="col-md-4 d-flex flex-column">
                <div class="h-100 text-center">
                    <span>{{ trans('main.Status') }}:</span>
                    @if ($book->status == Book::STATUS_PENDING)
                        <div class="text-warning d-inline-block">{{trans('main.Pending')}}</div>
                        <br />
                        @if ($book->cancellableByUser() && $book->isBeforeBook24Hrs())
                            @if ($book->provide_online_type == Service::PROVIDE_ONLINE_TYPE)
                                <button class="btn btn-danger btn-responsive mt-3 mb-4" onclick="onCancelOrder(event, 'cancel_direct', '{{ $book->status }}')">{{trans('main.Cancel the order')}}</button>
                            @else
                                @if ($book->is_paid_online == 1)
                                    @if ($book->total_fee > $user->wallet_balance) 
                                        <button class="btn btn-danger btn-responsive mt-3 mb-4" onclick="onCancelOrder(event, 'cancel_credit', '{{ $book->status }}')">{{trans('main.Add credit and cancel')}}</button>
                                    @else
                                        <button class="btn btn-danger btn-responsive mt-3 mb-4" onclick="onCancelOrder(event, 'cancel_after_confirm', '{{ $book->status }}')">{{trans('main.Cancel the order')}}</button>
                                    @endif
                                @else
                                    @if ($book->booking_confirm == Service::BOOKING_CONFIRM)
                                        <button class="btn btn-danger btn-responsive mt-3 mb-4" onclick="onCancelOrder(event, 'cancel_after_confirm', '{{ $book->status }}')">{{trans('main.Cancel the order')}}</button>
                                    @else
                                        @if ($book->total_fee > $user->wallet_balance)
                                            <button class="btn btn-danger btn-responsive mt-3 mb-4" onclick="onCancelOrder(event, 'cancel_credit', '{{ $book->status }}')">{{trans('main.Add credit and cancel')}}</button>
                                        @else
                                            <button class="btn btn-danger btn-responsive mt-3 mb-4" onclick="onCancelOrder(event, 'cancel_after_confirm', '{{ $book->status }}')">{{trans('main.Cancel the order')}}</button>
                                        @endif
                                    @endif
                                @endif
                            @endif
                        @endif
                    @elseif ($book->status == Book::STATUS_CANCEL)
                        <div class="text-danger d-inline-block">
                            @if ($book->deleted_by == 'buyer')
                                {{trans('main.Canceled by Customer')}}
                            @elseif (($book->deleted_by == 'admin'))
                                {{ trans('main.Canceled by Administrator') }}
                            @elseif (($book->deleted_by == 'auto'))
                                {{ trans('main.Canceled, no confirmation') }}
                            @else
                                {{trans('main.You canceled')}}
                            @endif
                        </div>
                    @elseif ($book->status == Book::STATUS_WAIT_CONFIRM)
                        <div class="text-warning d-inline-block">{{trans('main.Waiting Confirmation')}}</div><br />
                        <form method="POST" id="form-accept-order" action="{{ route('user.orders.accept') }}">
                            @csrf
                            <input type="hidden" name="book_id" value="{{ $book->id }}" />

                            @if ($book->is_paid_online == 1)
                                <button onclick="onAcceptOrder(event, 'confirm_direct_paid_online')" class="btn btn-primary btn-responsive mt-5">{{trans('main.Confirm')}}</button><br />
                            @else
                                @if($book->seller->wallet_balance < $book->total_fee)
                                    <button onclick="onAcceptOrder(event, 'confirm_charge_credit')" class="btn btn-primary btn-responsive mt-5">{{trans('main.Add Credit and Confirm')}}</button><br />
                                @else
                                    <button onclick="onAcceptOrder(event, 'confirm_direct')" class="btn btn-primary btn-responsive mt-5">{{trans('main.Confirm')}}</button><br />
                                @endif
                            @endif
                        </form>

                        @if ($book->cancellableByUser() && $book->isBeforeBook24Hrs())
                            @if ((strtotime($book->created_at) - time()) < (48 * 60 * 60))
                                <button class="btn btn-danger btn-responsive mt-3 mb-4" onclick="onCancelOrder(event, 'cancel_before_48hrs', '{{ $book->status }}')">{{trans('main.Cancel the order')}}</button>
                            @else
                                @if ($book->is_paid_online == 1 && $book->total_fee > $user->wallet_balance)
                                    <button class="btn btn-danger mt-8" onclick="onCancelOrder(event, 'cancel_credit', '{{ $book->status }}')">{{trans('main.Add credit and cancel')}}</button>
                                @else
                                    <button class="btn btn-danger mt-8 mb-8" onclick="onCancelOrder(event, 'cancel_direct', '{{ $book->status }}')">{{trans('main.Cancel the order')}}</button>
                                @endif
                            @endif
                        @endif
                    @elseif ($book->status == Book::STATUS_PROVIDED || $book->status == Book::STATUS_COMPLETED)
                        <div class="text-primary d-inline-block">{{trans('main.Provided')}}</div>
                    @endif

                    <form method="POST" id="form-cancel-order" action="{{ route('user.orders.cancel') }}">
                        @csrf
                        <input type="hidden" name="book_id" value="{{ $book->id }}" />
                    </form>
                </div>

                @if ($book->provide_online_type == Service::PROVIDE_OFFLINE_TYPE)
                    <div style="margin-top: auto;">
                        @if (request()->get('from_balance'))
                            <a href="{{ URL::route('user.balance.show') }}" class="btn btn-light-primary" style="width: 120px;">
                                {{ trans('main.Back') }}
                            </a>
                        @else
                            <a href="{{ URL::route('user.orders.index') }}" class="float-right btn btn-light-primary" style="width: 120px;">
                                {{ trans('main.Back') }}
                            </a>
                        @endif
                    </div>
                @endif
                
            </div>
            {{-- ./right column --}}
        </div>

        @if ($book->provide_online_type == Service::PROVIDE_ONLINE_TYPE)
            @include('user.pages.book.online-order-messages', [
                'book' => $book,
                'user_type' => 'seller'])
        @else
            <div class="mb-4">
                @include('user.pages.order.describe')
            </div>
        @endif

        @if ($book->provide_online_type == Service::PROVIDE_ONLINE_TYPE)
            <div class="text-right mt-4">
                @if (request()->get('from_balance'))
                    <a href="{{ URL::route('user.balance.show') }}" class="btn btn-light-primary" style="width: 120px;">
                        {{ trans('main.Back') }}
                    </a>
                @else
                    <a href="{{ URL::route('user.orders.index') }}" class="btn btn-light-primary" style="width: 120px;">
                        {{ trans('main.Back') }}
                    </a>
                @endif
            </div>

            <div class="mb-4">
                @include('user.pages.order.describe')
            </div>
        @endif
    </div>
</section>
@endsection

@section('scripts')
{{ Html::script(adminAsset('js/pages/crud/forms/widgets/bootstrap-datepicker.js?v=7.0.5')) }}
{{ Html::script(userAsset('common-js/date_convert.js')) }}
@include('user.pages.book.online-order-message-js')
<script>
    var isBookingConfirm = "{{ $book->booking_confirm == Service::BOOKING_CONFIRM }}";
    var weeklyHolidays = [];
    @isset ($book->office)
        weeklyHolidays = @json($book->office->weeklyHolidays());
    @endisset

    function onAcceptOrder(event, mode) {
        event.preventDefault();

        var message = '';
        switch (mode) {
            case 'confirm_direct':
                message = "{{ trans('main.confirm_direct_message', ['fee_price' => $book->total_fee]) }}";
                break;
            case 'confirm_direct_paid_online':
                message = "{{ trans('main.confirm_direct_message_paid_online', ['fee_price' => $book->total_fee]) }}";
                break;
            case 'confirm_charge_credit':
                message = "{{ trans('main.confirm_credit_message', ['fee_price' => $book->total_fee]) }}";
                break;
        }

        Swal.fire({
            title: message,
            showCancelButton: true,
            confirmButtonText: "{{ trans('main.confirm_pop-up') }}",
            cancelButtonText: "{{ trans('main.cancel_pop-up') }}"
        }).then(function(result) {
            if (result.value) {
                $('#form-accept-order').submit();
            }
        });
    }

    function onCancelOrder(event, mode, bookStatus){
        event.preventDefault();

        var message = '';
        switch (mode) {
            case 'cancel_direct':
                message = "{{ trans('main.cancel_direct_message', ['fee_price' => $book->total_fee]) }}";
                break;
            case 'cancel_credit':
                message = "{{ trans('main.cancel_credit_message', ['fee_price' => $book->total_fee]) }}";
                break;
            case 'cancel_after_confirm':
                message = "{{ trans('main.cancel_after_confirmation_fee_already_paid_message', ['buyer_name' => $book->user->name]) }}";
                break;
            case 'cancel_before_48hrs':
                message = "{{ trans('main.cancel_within_48hrs_no_credit_need_message', ['buyer_name' => $book->user->name]) }}";
                break;
        }

        Swal.fire({
            title: message,
            showCancelButton: true,
            confirmButtonText: "{{ trans('main.confirm_pop-up') }}",
            cancelButtonText: "{{ trans('main.cancel_pop-up') }}"
        }).then(function(result) {
            if (result.value) {
                $('#form-cancel-order').submit();
            }
        });
    }

    $(function() {
        var deliveryDate = new Date("{{ strtotime($book->delivery_date) }}" * 1000);
        var newStartDate = new Date();
        var locale = "{{ App::getLocale() ?: 'en' }}";
        newStartDate.setDate(deliveryDate.getDate() + 1);

        $('.date-picker').datepicker({
            language: locale,
            daysOfWeekDisabled: weeklyHolidays,
            format: 'dd/mm/yyyy',
            startDate: newStartDate,
        }).on('changeDate', function (e) {
            $('#new-delivery-date').val(e.format('yyyy-mm-dd'));
        });
    })   
</script>
@endsection
