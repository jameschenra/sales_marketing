@extends('admin.layout.default')

@section('styles')
    <link href="{{ adminAsset('plugins/custom/datatables/datatables.bundle.css') }}" rel="stylesheet" type="text/css"/>
@endsection

{{-- Content --}}
@section('content')
    @include('admin.components.success-alert')

    <div class="card card-custom">
        <div class="card-header flex-wrap border-0 pt-6 pb-0">
            <div class="card-title w-100 justify-content-between">
                <h3 class="card-label">@lang('main.Transaction')</h3>
            </div>
        </div>

        <div class="card-body">
            <table class="table table-bordered table-hover" id="kt_datatable">
                <thead>
                    <tr>
                        <th class="border-top text-capitalize">
                            {{ trans('main.ID') }}
                        </th>
                        <th class="border-top text-capitalize">{{ trans('main.Date') }}
                        </th>
                        <th class="border-top text-capitalize">{{ trans('main.Transaction type') }}
                        </th>
                        <th class="border-top text-capitalize">{{ trans('main.Service') }}
                        </th>
                        <th class="border-top text-capitalize">{{ trans('main.Description') }}
                        </th>
                        <th class="border-top text-capitalize">{{ trans('main.Sender') }}
                        </th>
                        <th class="border-top text-capitalize">{{ trans('main.Receiver') }}
                        </th>
                        <th class="border-top text-capitalize">{{ trans('main.Transaction amount') }}
                        </th>
                        <th class="border-top text-capitalize">{{ trans('main.Refunded') }}
                        </th>
                        <th class="th-action">{{ trans('main.Action') }}</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($transactions as $key => $transaction)
                        <tr>
                            <td>{{ $transaction->id }}@if($transaction->receiver_id == 0 || $transaction->sender_id == 0)f @endif </td>
                            <td>{{ $transaction->created_at }}</td>
                            <td>
                            @php
                                $book = $transaction->book;
                                $refundable = false;
                                $with_fee = false;
                            @endphp
                            @if($transaction->service_id > 0 && $transaction->service && $transaction->service->user)
                                @if($transaction->receiver_id > 0 && $transaction->sender_id == 0 && $book->price == 0)
                                    {{ trans('main.Refund Fee for the free service') }}
                                @elseif($transaction->receiver_id > 0 && $transaction->sender_id == 0)
                                    {{ trans('main.Refund Fee for the service') }}
                                @elseif($transaction->sender_id == $transaction->service->user->id && $transaction->receiver_id > 0)
                                    {{ trans('main.Refund for the service') }}
                                @elseif($transaction->receiver_id > 0 && $transaction->sender_id > 0)
                                    {{ trans('main.Payment online for the service') }}
                                    @php
                                        $refundable = true;
                                        $with_fee = true;
                                    @endphp
                                @elseif($transaction->receiver_id == 0 && $book->price == 0)
                                    {{ trans('main.Fee for the free service') }}
                                    @php
                                        $refundable = true;
                                    @endphp
                                @elseif($transaction->receiver_id == 0)
                                    {{ trans('main.Fee for the service') }}
                                    @php
                                        $refundable = true;
                                    @endphp
                                @endif
                            @elseif($transaction->sender_id == 0 && !$transaction->book_id)
                                {{ trans('main.Top up balance') }}
                            @elseif($transaction->sender_id != 0 && $transaction->receiver_id == 0 && !$transaction->book_id)
                                {{ trans('main.Withdraw-credit-balance') }}
                            @endif
                            <td>
                                @if($transaction->service_id == 0)
                                    {{trans('main.Withdraw')}}
                                @elseif($transaction->service)
                                    {{ $transaction->service->name }}
                                @endif
                            </td>
                            <td>
                                @if ($transaction->receiver_id == 0 && $transaction->sender_id > 0 && $book && $book->is_paid_online && $book->price != 0)
                                    {{ trans('main.paid fee by') }}
                                @elseif ($transaction->receiver_id > 0 &&
                                $transaction->sender_id > 0 &&
                                $book &&
                                $book->is_paid_online &&
                                    $book->price != 0 &&
                                    $book->user_id == $transaction->receiver_id)
                                    {{ trans('main.booked by') }}
                                @elseif ($transaction->receiver_id == 0 && $transaction->sender_id > 0 && $book && !$book->is_paid_online && $book->price != 0)
                                    {{ trans('main.paid fee for booking in office by') }}
                                @elseif ($transaction->receiver_id > 0 && $transaction->sender_id == 0 && $book && !$book->is_paid_online && $book->price != 0 && $book->deleted_by == 'buyer')
                                    {{ trans('main.refund fee for canceled booking in office by user') }}
                                @elseif ($transaction->receiver_id > 0 && $transaction->sender_id == 0 && $book && $book->is_paid_online && $book->price != 0 && $book->deleted_by == 'buyer')
                                    {{ trans('main.refund fee for canceled booking by user') }}
                                @elseif ($transaction->receiver_id > 0 && $transaction->sender_id > 0 && $book && $book->is_paid_online && $book->price != 0 && $book->deleted_by == 'seller')
                                    {{ trans('main.refund service cost to user for canceled booking by company') }}
                                @elseif ($transaction->receiver_id > 0 && $transaction->sender_id > 0 && $book && $book->is_paid_online && $book->price != 0 && $book->deleted_by == 'buyer')
                                    {{ trans('main.refund service cost to user for canceled booking by user') }}
                                @elseif ($transaction->receiver_id > 0 && $transaction->sender_id > 0 && $book && $book->is_paid_online && $book->price != 0 && $book->deleted_by == 'admin')
                                    {{ trans('main.refund service cost to user by admin') }}
                                @elseif ($transaction->receiver_id > 0 && $transaction->sender_id == 0 && $book && $book->is_paid_online && $book->price != 0 && $book->deleted_by == 'admin')
                                    {{ trans('main.refund fee by admin') }}
                                @endif
                            </td>
                            <td>@if ($transaction->sender){{$transaction->sender->name}} @endif</td>
                            <td>@if ($transaction->receiver){{$transaction->receiver->name}} @endif</td>
                            <td class="text-success" style="text-align: center;">
                                {{ '€'.number_format($transaction->amount, 2) }}
                            </td>
                            <td>
                                @if ($transaction->refunded)
                                    {{ trans('main.Yes') }}
                                @endif
                            </td>
                            <td>
                                @if ($refundable && !$transaction->refunded)
                                    <a href="#" class="btn btn-sm btn-info" onclick="refund_action(@if($with_fee) true @else false @endif, {{ $transaction->id }} )">
                                        <i class="fa fa-exchange" aria-hidden="true"></i> {{ trans('main.Refund') }}
                                    </a>
                                @endif

                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>

            <br />
            <div class="float-right">{{ $transactions->links() }}</div>
            <div class="clearfix"></div>
        </div>

    </div>
@endsection


{{-- Scripts Section --}}
@section('scripts')
    {{-- vendors --}}
    <script src="{{ adminAsset('plugins/custom/datatables/datatables.bundle.js') }}" type="text/javascript"></script>

    {{-- page scripts --}}
@endsection
