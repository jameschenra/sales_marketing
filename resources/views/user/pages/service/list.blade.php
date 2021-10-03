{{-- Extends layout --}}
@extends('user.layout.default')

@section('styles')
    {{ Html::style(adminAsset('plugins/custom/datatables/datatables.bundle.css?v=7.0.5')) }}
<style>
    .btn-filter {
        min-width: 152px;
        margin-right: 10px;
    }

    #tbl-my-service_filter {
        text-align: right;
    }

    #tbl-my-service_filter label{
        text-align: left;
    }

    #tbl-my-service_wrapper div.row:nth-child(2)>div{
        overflow: auto;
    }

    @media (max-width: 767px) {
        #tbl-my-service_length {
            text-align: right;
        }

        #tbl-my-service_wrapper td:nth-child(3) {
            white-space: nowrap;
        }
    }
</style>
@endsection

@section('content')

<section class="bg-content d-flex align-items-center">
    <div class="container">
        <h3 class="mb-10 font-weight-bold text-dark">@lang('main.My Services')</h3>
        
        <div>
            <a href="{{route('user.service.mylist')}}" class="btn btn-primary btn-filter mb-4 d-block d-sm-inline-block">
                @lang('main.All') ({{ $num_services }})
            </a>
            <a href="{{route('user.service.mylist', ['filter' => 'published'])}}" class="btn btn-success btn-filter mb-4 d-block d-sm-inline-block">
                @lang('main.Published-main-service-page') ({{ $published }})
            </a>
            <a href="{{route('user.service.mylist', ['filter' => 'drafts'])}}" class="btn btn-warning btn-filter mb-4 d-block d-sm-inline-block">
                @lang('main.Drafts') ({{ $drafts }})
            </a>
            <a href="{{route('user.service.create')}}" class="btn btn-primary btn-filter float-none float-sm-right mb-4 d-block d-sm-inline-block">@lang('main.Create Service')</a>
        </div>

        <div class="tbl-container mt-10">
            <table class="table table-separate table-head-custom table-checkable" id="tbl-my-service">
                <thead>
                    <tr>
                        <th class="border-top"></th>
                        <th class="border-top">@lang('main.Service Name')</th>
                        <th class="border-top">@lang('main.Created At')</th>
                        <th class="border-top">@lang('main.Price')</th>
                        <th class="border-top">@lang('main.Status')</th>
                        <th class="border-top">@lang('main.action')</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($services as $service)
                        <tr>
                            <td><img width="100" src="{{ HTTP_SERVICE_PATH . $service->photo }} " alt=""/></td>
                            <td>{{ $service->name }}</td>
                            <td>{{ date("d-m-Y H:i", strtotime($service->created_at)) }}</td>
                            <td>
                                <strong>
                                    {{ $service->price > 0 ? '€ ' . $service->price : trans('main.Free Service') }}
                                </strong>
                            </td>
                            <td>{{ $service->active == 0 ? trans('main.Draft') : trans('main.Published') }}</td>
                            <td>
                                <a href="{{ URL::route('user.service.edit', ['id' => $service->id]) }}" class="btn btn-sm btn-clean btn-icon" title="Edit details">
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
