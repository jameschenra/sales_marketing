@php
    use App\Models\Book;
    use App\Enums\UserType;
    use App\Models\TransactionOfBooking;
@endphp
{{-- Extends layout --}}
@extends('user.layout.default')

@section('styles')
    {{ Html::style(adminAsset('plugins/custom/datatables/datatables.bundle.css?v=7.0.5')) }}
    {{ Html::style(userAsset('pages/balance/balance.css')) }}
@endsection

@section('content')
<section class="bg-content container">
    {{-- title --}}
    <h3 class="mb-10 font-weight-bold text-dark">@lang('main.Balance')</h3>
    {{-- ./title --}}

    <div class="dashboard_main">
        {{-- heading --}}
        <div class="heading">
            <div class="row">
                @if (($user->type == UserType::BUYER) || $is_service_completed)
                    {{-- available balance --}}
                    <div class="col-md col-12">
                        <div class="card">
                            <a href="{{ URL::route('user.balance.show', ['filter' => 'available_balance']) }}">
                                <div class="card-body text-dark"
                                    data-balance="{{ number_format($user->wallet_balance, 2) }}">
                                    <div class="d-flex">
                                        <div class="circle bg-available_balance mr-2">
                                            <img src="{{ imageAsset('available_balance.png') }}"
                                                alt="weredy theme">
                                        </div>
                                        <div class="card_body_text">
                                            <h4 class="mb-0">€{{ number_format($user->wallet_balance, 2) }}</h4>
                                            <div class="text-dark-50">{{ trans('main.Available-balance') }} </div>
                                        </div>
                                    </div>
                                </div>
                            </a>
                        </div>
                    </div>
                    {{-- ./available balance --}}
                @endif
                
                @if ($is_service_completed)
                    {{-- not available balance --}}
                    <div class="col-md col-12">
                        <div class="card">
                            <a href="{{ URL::route('user.balance.show', ['filter' => 'not_available_balance']) }}">
                                <div class="card-body text-dark">
                                    <div class="d-flex">
                                        <div class="circle bg-not_available_balance mr-2">
                                            <img src="{{ imageAsset('balance-not-available.png') }}"
                                                alt="weredy theme">
                                        </div>
                                        <div class="card_body_text">
                                            <h4 class="mb-0">€{{ number_format($user->balance->pending_balance, 2) }}
                                            </h4>
                                            <div class="text-dark-50">{{ trans('main.Not Available Balance') }} </div>
                                        </div>
                                    </div>
                                </div>
                            </a>
                        </div>
                    </div>
                    {{-- ./not available balance --}}

                    {{-- total fees --}}
                    <div class="col-md col-12 {{ $user->type == UserType::BUYER ? 'd-none' : '' }}">
                        <div class="card">
                            <a href="{{ URL::route('user.balance.show', ['filter' => 'fees']) }}">
                                <div class="card-body text-dark">
                                    <div class="d-flex">
                                        <div class="circle bg-total_fee mr-2">
                                            <img src="{{ imageAsset('total_fee.png') }}" alt="weredy theme">
                                        </div>
                                        <div class="card_body_text">
                                            <h4 class="mb-0">€{{ number_format(-1 * $user->getTotalFees(), 2) }}</h4>
                                            <div class="text-dark-50">{{ trans('main.Total-fees-balance') }} </div>
                                        </div>
                                    </div>
                                </div>
                            </a>
                        </div>
                    </div>
                    {{-- ./total fees --}}

                    {{-- total withdraw --}}
                    <div class="col-md col-12">
                        <div class="card">
                            <a href="{{ URL::route('user.balance.show', ['filter' => 'withdraw']) }}">
                                <div class="card-body text-dark">
                                    <div class="d-flex">
                                        <div class="circle bg-total_withdrawn mr-2">
                                            <img src="{{ imageAsset('total_withdrawn.png') }}" alt="weredy theme">
                                        </div>
                                        <div class="card_body_text">
                                            <h4 class="mb-0">€{{ number_format($user->getTotalWithdraws(), 2) }}</h4>
                                            <div class="text-dark-50">{{ trans('main.Total-withdraw-balance') }} </div>
                                        </div>
                                    </div>
                                </div>
                            </a>
                        </div>
                    </div>
                    {{-- ./total withdraw --}}
                @else
                    {{-- total withdraw --}}
                    <div class="col-md col-12">
                        <div class="card">
                            <a href="{{ URL::route('user.balance.show', ['filter' => 'withdraw']) }}">
                                <div class="card-body text-dark">
                                    <div class="d-flex">
                                        <div class="circle bg-total_withdrawn mr-2">
                                            <img src="{{ imageAsset('total_withdrawn.png') }}" alt="weredy theme">
                                        </div>
                                        <div class="card_body_text">
                                            <h4 class="mb-0">€{{ number_format($user->getTotalWithdraws(), 2) }}</h4>
                                            <div class="text-dark-50">{{ trans('main.Total-withdraw-balance') }} </div>
                                        </div>
                                    </div>
                                </div>
                            </a>
                        </div>
                    </div>
                    {{-- ./total withdraw --}}
                    <div class="col-md"></div>
                    <div class="col-md"></div>
                    <div class="col-md"></div>
                @endif

            </div>
        </div>
        {{--./ heading --}}
        <br /><br />

        <div class="row">
            {{-- add credit form --}}
            <div class="col-md-6">
                <div class="accordion accordion-toggle-arrow" id="accordionCredit">
                    <div class="card">
                        <div class="card-header" id="headingCreditForm">
                            <div class="card-title text-dark font-weight-bolder" data-toggle="collapse" data-target="#collapse-credit-form">
                                @lang('main.Add-credit-balance')
                            </div>
                        </div>

                        <div id="collapse-credit-form" class="collapse show" data-parent="#accordionCredit">
                            <div class="card-body">
                                <form method="post" action="{{ route('user.balance.do-recharge') }}"
                                    id="form-credit-amount" class="p-0 border-0 m-t-20">
                                    @csrf
                                    <div class="form-group">
                                        <label>{{ trans('main.Enter-amount-add-balance') }}<span class="text-danger">*</span></label>
                                        <input type="text" class="form-control input-decimal" name="amount"
                                            id="add-credit-balance-amount"
                                            value="{{ old('amount') }}"
                                            placeholder="{{ trans('main.Amount') }}">
                                        @if($errors->first('amount'))
                                            <span class="help-block error d-block" for="amount" id="amount-error">
                                                <strong>{{ $errors->first('amount') }}</strong>
                                            </span>
                                        @endif
                                        {{-- <input type="text" class="form-control" placeholder="Amount minimun to add $10,00.."> --}}

                                        <label style="color: red; display: none; margin-bottom: 0;"
                                                id="add-credit-balance-error"></label>
                                    </div>

                                    <div class="form-group text-right">
                                        <button class="btn btn-primary" type="submit"
                                            id="add-credit-balance-button" value="button">{{ trans('main.Add-credit-balance') }}</button>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            {{-- ./add credit form --}}
            
            {{-- withdraw credit form --}}
            <div class="col-md-6">
                <div class="accordion accordion-toggle-arrow" id="accordionPaypal">
                    <div class="card">
                        <div class="card-header" id="headingWithdrawForm">
                            <div class="card-title text-dark font-weight-bolder" data-toggle="collapse" data-target="#collapse-paypal-form">
                                @lang('main.Withdraw-credit-balance')
                            </div>
                        </div>

                        <div id="collapse-paypal-form" class="collapse show" data-parent="#accordionPaypal">
                            <div class="card-body">
                                <form method="post" action="{{ route('user.balance.withdraw') }}"
                                    id="withdraw-form" class="p-0 border-0 m-t-20">
                                    @csrf

                                    <input type="hidden" name="2fa_code" id="2fa_code" value="">

                                    <div class="form-group">
                                        <label>{{ trans('main.Your-paypal-account-balance') }} <span class="text-danger">*</span></label>
                                        <input type="email" class="form-control" name="email"
                                            id="withdraw-email"
                                            value="{{ old('email') }}" placeholder="{{ trans('main.Your-paypal-account-balance') }}"
                                            required>
                                        @if($errors->first('email'))
                                            <span class="help-block error d-block" for="email" id="email-error">
                                                <strong>{{ $errors->first('email') }}</strong>
                                            </span>
                                        @endif
                                    </div>
                                    <div class="form-group">
                                        <label>{{ trans('main.Enter-amount-withdraw-balance') }}<span class="text-danger">*</span></label>
                                        <input type="text" class="form-control input-decimal" name="withdraw_amount"
                                            id="withdraw-amount"
                                            value="{{ old('withdraw_amount') }}"
                                            placeholder="{{ trans('main.Amount') }}"
                                            required>
                                        @if($errors->first('withdraw_amount'))
                                            <span class="help-block error d-block" for="amount" id="amount-error">
                                                <strong>{{ $errors->first('withdraw_amount') }}</strong>
                                            </span>
                                        @endif

                                        <span class="help-block error d-none" id="withdraw-amount-error"></span>
                                    </div>
                                    <div class="form-group text-right">
                                        <button class="btn btn-primary" type="submit"
                                            id="withdraw-form-submit-btn" value="button">{{ trans('main.Withdraw') }}</button>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            {{-- ./withdraw credit form --}}
        </div>
        <br /><br />

        {{-- transaction history --}}
        <div class="tbl-container">
            <hr />
            <h4>@lang('main.Transactions-Invoices-list')</h4>
            <div class="table-responsive">
                <table class="table table-separate table-head-custom table-checkable" id="tbl-my-service">
                    <thead>
                        <tr>
                            <th class="border-top">@lang('main.ID')</th>
                            <th class="border-top">@lang('main.Date')</th>
                            <th class="border-top">@lang('main.Order Number')</th>
                            <th class="border-top">@lang('main.Transaction type')</th>
                            <th class="border-top">@lang('main.Service')</th>
                            <th class="border-top">@lang('main.Description')</th>
                            <th class="border-top">@lang('main.Customer')</th>
                            <th class="border-top">@lang('main.Debit')</th>
                            <th class="border-top">@lang('main.Credit')</th>
                            <th class="border-top">@lang('main.Invoices')</th>
                        </tr>
                    </thead>
                    <tbody>
                    @foreach ($transactions as $transaction)
                        @php
                            $book = $transaction->book;
                        @endphp
                        {{-- if not withdraw transaction --}}
                        @if ($transaction->service_id > 0)
                            {{-- if seller's transaction --}}
                            @if ($user->services->find($transaction->service_id))
                                <tr>
                                    <td>
                                        {{ $transaction->id }}
                                    </td>
                                    <td>{{ $transaction->created_at }}</td>
                                    <td>
                                        <a href="{{ route('user.orders.view', [
                                            'id' => $transaction->book_id,
                                            'from_balance' => 1,
                                        ]) }}">{{ $transaction->book_id }}</a>
                                    </td>
                                    <td>
                                        @switch ($transaction->transaction_type)
                                            @case(TransactionOfBooking::TYPE_NORMAL)
                                                @lang('main.Payment online for the service')
                                                @break;
                                            @case(TransactionOfBooking::TYPE_FEE)
                                                @if ($book->price == 0)
                                                    @lang('main.Fee for the free service')
                                                @else
                                                    @lang('main.Fee for the service')
                                                @endif
                                                @break;
                                            @case(TransactionOfBooking::TYPE_NORMAL_REFUND)
                                                @lang('main.Refund for the service')
                                                @break;
                                            @case(TransactionOfBooking::TYPE_FEE_REFUND)
                                                @if ($book->price == 0)
                                                    @lang('main.Refund Fee for the free service')
                                                @else
                                                    @lang('main.Refund Fee for the service')
                                                @endif                                                        
                                                @break;
                                        @endswitch
                                    </td>
                                    <td>
                                        @if ($transaction->service_id == 0)
                                            {{ trans('main.Withdraw') }}
                                        @elseif ($transaction->service)
                                            {{ $transaction->service->name }}
                                        @endif
                                    </td>
                                    <td>
                                        @switch ($transaction->transaction_type)
                                            @case(TransactionOfBooking::TYPE_NORMAL)
                                            @case(TransactionOfBooking::TYPE_FEE)
                                                @if ($book->price == 0)
                                                    @lang('main.booked by')
                                                @elseif ($book->is_paid_online == 1)
                                                    @lang('main.paid online by')
                                                @else
                                                    @lang('main.to pay in office by')
                                                @endif
                                                @break;
                                            @case(TransactionOfBooking::TYPE_NORMAL_REFUND)
                                            @case(TransactionOfBooking::TYPE_FEE_REFUND)
                                                @if ($book->price == 0)
                                                    @lang('main.booked and canceled by')
                                                @elseif ($book->is_paid_online == 1)
                                                    @lang('main.paid online and canceled by')
                                                @else
                                                    @lang('main.to pay in office and canceled by')
                                                @endif                                                
                                                @break;
                                        @endswitch
                                    </td>
                                    <td class="type">
                                        @if ($transaction->receiver_id == $user->id && $transaction->sender_id == 0)
                                            {{ $book->user->name }}
                                        @elseif ($transaction->receiver_id == $user->id)
                                            {{ $transaction->sender->name }}
                                        @elseif ($transaction->sender_id == $user->id && $transaction->receiver_id == 0)
                                            {{ $book->user->name }}
                                        @elseif ($transaction->sender_id == $user->id &&
                                            $book->deleted_by == 'seller')
                                            {{ $transaction->sender->name }}
                                        @elseif ($transaction->sender_id == $user->id)
                                            {{ $transaction->receiver->name }}
                                        @endif
                                    </td>
                                    <td class="text-danger" style="text-align: center;">
                                        {!! $transaction->sender_id == $user->id ? '-&nbsp;€' . number_format($transaction->amount, 2) : '' !!}
                                    </td>
                                    <td class="text-success" style="text-align: center;">
                                        {!! $transaction->receiver_id == $user->id ? '€' . number_format($transaction->amount, 2) : '' !!}
                                    </td>
                                    <td class="action">
                                        <a href="{{ route('user.balance.download_invoice', [$transaction->id]) }}"
                                            target="_blank" class="btn btn-primary p-r-10">
                                            <span class="fa fa-download"></span> {{ trans('main.Download') }}
                                        </a>
                                    </td>
                                </tr>
                            {{-- if buyer's transaction --}}
                            @else
                                <tr>
                                    <td>{{ $transaction->id }}</td>
                                    <td>{{ $transaction->created_at }}</td>
                                    <td>
                                        <a href="{{ route('user.book.detail', [
                                            'id' => $transaction->book_id,
                                            'from_balance' => 1
                                        ]) }}">{{ $transaction->book_id }}</a>
                                    </td>
                                    <td>
                                        @switch ($transaction->transaction_type)
                                            @case(TransactionOfBooking::TYPE_NORMAL)
                                                @lang('main.Online purchase for the service')
                                                @break;
                                            @case(TransactionOfBooking::TYPE_FEE)
                                                @if ($book->price == 0)
                                                    @lang('main.Fee for the free service')
                                                @else
                                                    @lang('main.Fee for the service')
                                                @endif
                                                @break;
                                            @case(TransactionOfBooking::TYPE_NORMAL_REFUND)
                                                @lang('main.Refund for the service')
                                                @break;
                                            @case(TransactionOfBooking::TYPE_FEE_REFUND)
                                                @if ($book->price == 0)
                                                    @lang('main.Refund Fee for the free service')
                                                @else
                                                    @lang('main.Refund Fee for the service')
                                                @endif                                                        
                                                @break;
                                        @endswitch
                                    </td>
                                    <td class="detail">
                                        {{ $transaction->service->name ?? '' }}
                                    </td>
                                    <td class="type">
                                        @switch ($transaction->transaction_type)
                                            @case(TransactionOfBooking::TYPE_NORMAL)
                                                @lang('main.paid to')
                                                @break;
                                            @case(TransactionOfBooking::TYPE_FEE)
                                                @if ($book->price == 0)
                                                    @lang('main.Fee for the free service')
                                                @else
                                                    @lang('main.Fee for the service')
                                                @endif
                                                @break;
                                            @case(TransactionOfBooking::TYPE_NORMAL_REFUND)
                                            @case(TransactionOfBooking::TYPE_FEE_REFUND)
                                                @lang('main.paid online and canceled by')
                                                @break;
                                        @endswitch
                                    </td>
                                    <td>
                                        @switch ($transaction->transaction_type)
                                            @case(TransactionOfBooking::TYPE_NORMAL)
                                            @case(TransactionOfBooking::TYPE_FEE)
                                                {{ $transaction->receiver->name }}
                                                @break;
                                            @case(TransactionOfBooking::TYPE_NORMAL_REFUND)
                                            @case(TransactionOfBooking::TYPE_FEE_REFUND)
                                                @if ($book->deleted_by == 'seller')
                                                    {{ $transaction->sender->name }}
                                                @else
                                                    {{ $transaction->receiver->name }}
                                                @endif
                                                @break;
                                        @endswitch
                                    </td>
                                    <td class="text-danger" style="text-align: center;">
                                        {!! $transaction->sender_id == $user->id ? '-&nbsp;€' . number_format($transaction->amount, 2) : '' !!}
                                    </td>
                                    <td class="text-success" style="text-align: center;">
                                        {{ $transaction->receiver_id == $user->id ? '€' . number_format($transaction->amount, 2) : '' }}
                                    </td>
                                    <td class="action">
                                        <a href="{{ route('user.balance.download_invoice', [$transaction->id]) }}"
                                            target="_blank" class="btn btn-primary p-r-10">
                                            <span class="fa fa-download"></span> {{ trans('main.Download') }}
                                        </a>
                                    </td>
                                </tr>
                            @endif                                
                        @else
                            @if ($transaction->transaction_type == TransactionOfBooking::TYPE_WITHDRAW)
                                <tr>
                                    <td>{{ $transaction->id }}</td>
                                    <td>{{ $transaction->created_at }}</td>
                                    <td></td>
                                    <td>{{ trans('main.balance.transaction-history.withdraw') }}</td>
                                    <td></td>
                                    <td class="detail">Withdraw</td>
                                    <td class="type"></td>
                                    <td class="text-danger" style="text-align: center;">
                                        {!! '-&nbsp;€' . number_format($transaction->amount, 2) !!}
                                    </td>
                                    <td class="text-success" style="text-align: center;"></td>
                                    <td class="action"></td>
                                </tr>
                            @elseif ($transaction->transaction_type == TransactionOfBooking::TYPE_CREDIT)
                                <tr>
                                    <td>{{ $transaction->id }}</td>
                                    <td>{{ $transaction->created_at }}</td>
                                    <td></td>
                                    <td>{{ trans('main.Total balance topped-up') }}</td>
                                    <td></td>
                                    <td class="detail">Add Credit</td>
                                    <td class="type"></td>
                                    <td></td>
                                    <td class="text-success" style="text-align: center;">
                                        {!! '-&nbsp;€' . number_format($transaction->amount, 2) !!}
                                    </td>
                                    <td class="action"></td>
                                </tr>
                            @endif
                        @endif
                    @endforeach
                    </tbody>
                </table>
            </div>
            <br />
            <div class="float-right">{{ $transactions->links() }}</div>
        </div>
        {{-- ./transaction history --}}
    </div>
</section>

<div class="modal fade" id="confirm-withdraw-modal" tabindex="-1" role="dialog" aria-labelledby="confirmModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title w-100 text-center">@lang('main.pop.up.withdraw.title')</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <i aria-hidden="true" class="ki ki-close"></i>
                </button>
            </div>
            <div class="modal-body">
                <p>@lang('main.balance.withdraw.interface.popup_title')</p>
                <div class="form-group">
                    <input type="text" class="form-control" id="modal_2fa_code" value="" placeholder="" required>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-light-primary font-weight-bold" data-dismiss="modal">
                    @lang('main.balance.withdraw.interface.popup_button_cancel')
                </button>
                <button type="button" class="btn btn-primary font-weight-bold" onclick="checkBookingConfirmCode()">
                    @lang('main.balance.withdraw.interface.popup_button_confirm')
                </button>
            </div>
        </div>
    </div>
</div>
@endsection

@section('scripts')
    {{ Html::script(adminAsset('plugins/custom/datatables/datatables.bundle.js?v=7.0.5')) }}
    @include('user.pages.balance.balance-js')
@endsection
