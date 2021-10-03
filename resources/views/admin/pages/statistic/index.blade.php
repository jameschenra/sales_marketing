@extends('admin.layout.default')

@section('styles')
@endsection

{{-- Content --}}
@section('content')
    @include('admin.components.success-alert')

    <div class="card card-custom">
        <div class="card-header flex-wrap border-0 pt-6 pb-0">
            <div class="card-title w-100 justify-content-between">
                <h3 class="card-label">@lang('main.Plan') @lang('main.List')</h3>
            </div>
        </div>

        <div class="card-body">
            <ul>
                <!-- Balance -->
                <li>
                    <i class="icon-bar-chart"></i> 
                    <span class="title">{{ trans('main.Balance') }}</span>
                    <ul class="sub-menu">
                        <li>
                            <a href="{{ URL::route('admin.statistic.section', ['section' => 2]) }}">
                                <span class="title">{{ trans('main.Total balance topped-up') }}:</span>
                            </a>
                            {{ (int) $total_balance_topped_up_sum }}
                        </li>
                        <li>
                            <a href="{{ URL::route('admin.statistic.section', ['section' => 3]) }}">
                                <span class="title">{{ trans('main.Total balance not available') }}:</span>
                            </a>
                            {{ $total_not_available_sum }}
                        </li>
                        <li>
                                <span class="title">{{ trans('main.Total available balance') }}:</span>
                            {{ $total_available_balance_sum }}

                        </li>
                    </ul>
                </li>

                <!-- Refunds -->
                <li>
                    <i class="icon-bar-chart"></i> 
                    <span class="title">{{ trans('main.contact.refundsAndFee') }}</span>
                    <ul class="sub-menu">
                        <li>
                            <a href="{{ URL::route('admin.statistic.section', ['section' => 5]) }}">
                                <span class="title">{{ trans('main.Total balance refunded to buyers by a cancellation from users') }}:</span>
                            </a>
                            {{ $total_refunded_by_users_sum }}
                        </li>
                        <li>
                            <a href="{{ URL::route('admin.statistic.section', ['section' => 7]) }}">
                                <span class="title">{{ trans('main.Total balance refunded to buyers by a cancellation from site_name') }}:</span>
                            </a>
                            {{ $total_refunded_by_site_sum }}

                        </li>
                    </ul>
                </li>

                <!-- Fees -->
                <li>
                    <i class="icon-bar-chart"></i> 
                    <span class="title">{{ trans('main.Fees') }}</span>
                    <ul class="sub-menu">
                        <li>
                            <a href="{{ URL::route('admin.statistic.section', ['section' => 8]) }}">
                                <span class="title">{{ trans('main.Total pending fees') }}:</span>
                            </a>
                            {{ $total_pending_fees_sum }}
                        </li>
                        <li>
                            <a href="{{ URL::route('admin.statistic.section', ['section' => 9]) }}">
                                <span class="title">{{ trans('main.Total fees refunded to sellers by site_name') }}:</span>
                            </a>
                            {{ $total_fees_refunded_by_site_sum }}

                        </li>
                        <li>
                            <a href="{{ URL::route('admin.statistic.section', ['section' => 10]) }}">
                                <span class="title">{{ trans('main.Total available fees') }}:</span>
                            </a>
                            {{ $total_available_fees_sum }}

                        </li>

                    </ul>
                </li>

                <!-- Withdrawals -->
                <li>
                    <i class="icon-bar-chart"></i> 
                    <span class="title">{{ trans('main.Withdrawals') }}</span>
                    <ul class="sub-menu">
                        <li>
                            <a href="{{ URL::route('admin.statistic.section', ['section' => 11]) }}">
                                <span class="title">{{ trans('main.Total balance withdrawn by users') }}:</span>
                            </a>
                            {{ $total_withdrawn }}

                        </li>
                    </ul>
                </li>

                <!-- Earnings -->
                <li>
                    <i class="icon-bar-chart"></i> 
                    <span class="title">{{ trans('main.Earnings') }}</span>
                    <ul class="sub-menu">
                        <li>
                                <span class="title">{{ trans('main.Total balance earning by site_name') }}:</span>
                            {{ $total_site_earnings_sum }}
                        </li>
                    </ul>
                </li>
            </ul>
        </div>

    </div>
@endsection


{{-- Scripts Section --}}
@section('scripts')
@endsection
