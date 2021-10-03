@php
    use App\Models\Book;
@endphp
{{-- Extends layout --}}
@extends('user.layout.default')

@section('styles')
    {{ Html::style(userAsset('pages/order/order.css')) }}
@endsection

@section('content')
<section class="bg-content d-flex align-items-center">
    <div class="container">
        <h3 class="mb-10 font-weight-bold text-dark">@lang('main.Customers Orders')</h3>
        <div class="order-filter-wrapper d-flex flex-column flex-sm-row">
            <a href="{{ route('user.orders.index')}}" class="btn btn-all_services {{ $filter ? '' : 'selected' }}">
                @lang('main.book.All services ordered') ({{ $all_count }})</a>
            <a href="{{ route('user.orders.index', ['filter' => 'provided']) }}"
                class="btn btn-provided {{ $filter == 'provided' ? 'selected' : ''}}">
                @lang('main.book.Provided') ({{ $provided_count }})</a>
            <a href="{{ route('user.orders.index', ['filter' => 'pending']) }}"
                class="btn btn-pending {{ $filter == 'pending' ? 'selected' : '' }}">
                @lang('main.book.Pending') ({{ $pending_count }})</a>
            <a href="{{ route('user.orders.index', ['filter' => 'wait_confirm']) }}"
                class="btn btn-pending {{ $filter == 'wait_confirm' ? 'selected' : '' }}">
                @lang('main.book.Waiting Confirm') ({{ $waiting_confirm_count }})</a>
            <a href="{{ route('user.orders.index', ['filter' => 'canceled_by_user']) }}"
                class="btn btn-canceled {{ $filter == 'canceled_by_user' ? 'selected' : ''}}">
                @lang('main.book.Canceled by Customer') ({{ $canceled_by_user_count }})</a>
            <a href="{{ route('user.orders.index', ['filter' => 'canceled_by_you']) }}"
                class="btn btn-canceled {{ $filter == 'canceled_by_you' ? 'selected' : '' }}">
                @lang('main.You canceled') ({{ $canceled_by_you_count }})</a>
        </div>

        <div class="tbl-container mt-10">
            <table class="table table-separate table-head-custom" id="tbl-my-service">
                <thead>
                    <tr>
                        <th class="border-top">@lang('main.Order')</th>
                        <th class="border-top">{{ mb_strtolower(trans('main.Order Receipt')) }}</th>
                        <th class="border-top">@lang('main.Providing date')</th>
                        <th class="border-top">@lang('main.Service')</th>
                        <th class="border-top">@lang('main.Customer')</th>
                        <th class="border-top">@lang('main.Quantity')</th>
                        <th class="border-top">@lang('main.Payment')</th>
                        <th class="border-top">@lang('main.Price')</th>
                        <th class="border-top">@lang('main.Status')</th>
                        <th class="border-top">@lang('main.Details')</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($books as $key => $book)
                        <tr>
                            <td>{{ $book->id }}</td>
                            <td>{{ date('d/m/Y', strtotime($book->created_at)) }}</td>
                            <td>{{ date('d/m/Y', strtotime($book->book_date)) }}</td>
                            <td>
                                <a style="font-weight-light" href="{{ route('user.orders.view', $book->id) }}">
                                    {{ $book->service->name }}</a>
                            </td>
                            <td>
                                {{ $book->user->full_name }}
                            </td>
                            <td class="text-center">{{$book->number_of_booking}}</td>
                            <td>
                                @if($book->price == 0)
                                    @lang('main.Free Service')
                                @else
                                    @if($book->is_paid_online)
                                        {{trans('main.paid-online')}}
                                    @else
                                        @lang('main.To pay in office')
                                    @endif
                                @endif
                            </td>
                            <td>
                                @if ($book->price >= 1)
                                    €&nbsp;{{ $book->total_amount }}
                                @else
                                    @lang('main.Free Service')
                                @endif
                            </td>
                            <td>
                                @switch ($book->status)
                                    @case(Book::STATUS_PENDING)
                                        <span class="text-pending">@lang('main.Pending')</span>
                                        @break
                                    @case(Book::STATUS_PROVIDED)
                                    @case(Book::STATUS_COMPLETED)
                                        <span class="text-provided">@lang('main.Provided')</span>
                                        @break
                                    @case(Book::STATUS_WAIT_CONFIRM)
                                        <span class="text-danger">@lang('main.book.Waiting Confirm')</span>
                                        @break
                                    @case(Book::STATUS_CANCEL)
                                        @if ($book->deleted_by == 'buyer')
                                            <span class="text-canceled">@lang('main.Canceled by Customer')</span>
                                        @elseif ($book->deleted_by == 'admin')
                                            <span class="text-canceled">@lang('main.Canceled by Administrator')</span>
                                        @elseif ($book->deleted_by == 'auto')
                                            <span class="text-canceled">@lang('main.Canceled, no confirmation')</span>
                                        @else
                                            <span class="text-canceled">@lang('main.You canceled')</span>
                                        @endif
                                        @break
                                    @default
                                        @break
                                @endswitch
                            </td>
                            <td>
                                <a href="{{ route('user.orders.view', $book->id) }}"><i class="fa fa-eye" aria-hidden="true"></i></a>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

    </div>
</section>
@endsection

@section('scripts')
@endsection
