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
                <h3 class="card-label">@lang('main.Service')</h3>
                <a href="{{ route('admin.service.create') }}" class="btn btn-primary"><i class="flaticon2-plus-1"></i>@lang('main.Create')</a>
            </div>
        </div>

        <div class="card-body">
            <table class="table table-bordered table-hover" id="kt_datatable">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>{{ trans('main.Name') }}</th>
                        <th>{{ trans('main.Professional') }}</th>
                        <th>{{ trans('main.Email') }}</th>
                        <th>{{ trans('main.Phone') }}</th>
                        <th>{{ trans('main.Created At') }}</th>
                        <th class="th-action">{{ trans('main.Action') }}</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($services as $key => $service)
                        <tr>
                            <td>{{ ((Request::has('page') ? Request::get('page') : 1) - 1 ) * PAGINATION_SIZE + ($key + 1) }}</td>
                            <td>{{ $service->name }}</td>
                            <td>{{ $service->user->name }}</td>
                            <td>{{ $service->user->email }}</td>
                            <td>{{ $service->user->phone }}</td>                        
                            <td>{{ $service->created_at }}</td>
                            <td class="td-action">
                                @if ($service->active != 1)
                                    <a href="{{ URL::route('admin.service.active', $service->id) }}" class="btn btn-sm btn-icon"
                                        title="@lang('main.Active')">
                                        <i class="fa fa-check"></i>
                                    </a>
                                @endif
                                <a href="{{ route('admin.service.edit', $service->id) }}" class="btn btn-sm btn-icon"
                                    title="@lang('main.Edit')">
                                    <i class="fa fa-edit"></i>
                                </a>
                                <a href="{{ URL::route('admin.service.delete', $service->id) }}" class="btn btn-sm btn-icon"
                                    title="@lang('main.Delete')" id="btn-delete-item">
                                    <i class="fa fa-trash"></i>
                                </a>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>

            <br />
            <div class="float-right">{{ $services->links() }}</div>
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
