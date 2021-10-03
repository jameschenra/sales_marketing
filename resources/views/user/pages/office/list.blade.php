{{-- Extends layout --}}
@extends('user.layout.default')

@section('styles')
    {{ Html::style(adminAsset('plugins/custom/datatables/datatables.bundle.css?v=7.0.5')) }}
    <style>
        @media(max-width: 767px) {
            #tbl-my-office td {
                white-space: nowrap;
            }
        }
    </style>
@endsection

@section('content')
<section class="bg-content d-flex align-items-center">
    <div class="container">
        <h3 class="mb-10 font-weight-bold text-dark">@lang('main.My Offices')</h3>
        <div class="{{ $offices->count() >= 5 ? 'd-none' : '' }}">
            <a href="{{route('user.office.create')}}" class="btn btn-primary btn-filter float-right mb-5">@lang('main.Enter Office')</a>
        </div>
        <div class="clearfix"></div>

        <div class="tbl-container mt-10">
            <table class="table table-separate table-head-custom table-checkable" id="tbl-my-office">
                <thead>
                    <tr>
                        <th class="border-top">#</th>
                        <th class="border-top">@lang('main.Name')</th>
                        <th class="border-top">@lang('main.city')</th>
                        <th class="border-top">@lang('main.Address')</th>
                        <th class="border-top">@lang('main.Office Created At')</th>
                        <th class="border-top">@lang('main.action')</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($offices as $office)
                        <tr>
                            <td>{{ $loop->index + 1 }}</td>
                            <td>{{ $office->name }}</td>
                            <td>{{ $office->city->name ?? ''}}</td>
                            <td>{{ $office->address }}</td>
                            <td>{{ date("d-m-Y H:i", strtotime($office->created_at) ) }}</td>
                            <td>
                                <a href="{{ URL::route('user.office.edit', ['id' => $office->id]) }}" class="btn btn-sm btn-clean btn-icon" title="Edit details">
                                    <i class="la la-edit"></i>
                                </a>
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
{{ Html::script(adminAsset('plugins/custom/datatables/datatables.bundle.js?v=7.0.5')) }}
{{ Html::script(userAsset('pages/service/list.js')) }}
@endsection
