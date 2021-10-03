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
                <h3 class="card-label">@lang('main.Category')</h3>
                <a href="{{ route('admin.svccat.create') }}" class="btn btn-primary"><i class="flaticon2-plus-1"></i>@lang('main.Create')</a>
            </div>
        </div>

        <div class="card-body">
            <table class="table table-bordered table-hover" id="kt_datatable">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>{{ trans('main.Name') }}</th>
                        <th class="text-center">{{ trans('main.Icon') }}</th>
                        <th>{{ trans('main.Sub Categories') }}</th>
                        <th>{{ trans('main.Description') }}</th>
                        <th>{{ trans('main.Created At') }}</th>
                        <th class="th-action">{{ trans('main.Edit') }}</th>
                        <th class="th-action">{{ trans('main.Delete') }}</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($serviceCategories as $key => $value)
                        <tr>
                            <td>{{ ((Request::has('page') ? Request::get('page') : 1) - 1 ) * PAGINATION_SIZE + ($key + 1) }}</td>
                            <td>{{ $value->name }}</td>
                            <td class="text-center"><i class="{{ $value->icon }}"></i></td>
                            <td>{{ count($value->subCategories) }}</td>
                            <td>{{ $value->description }}</td>
                            <td>{{ $value->created_at }}</td>
                            <td>
                                <a href="{{ route('admin.svccat.edit', $value->id) }}" class="btn btn-sm btn-info">
                                    <span class="glyphicon glyphicon-edit"></span> {{ trans('main.Edit') }}
                                </a>
                            </td>
                            <td>
                                <a href="{{ route('admin.svccat.delete', $value->id) }}" class="btn btn-sm btn-danger" id="btn-delete-item">
                                    <span class="glyphicon glyphicon-trash"></span> {{ trans('main.Delete') }}
                                </a>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>

            <br />
            <div class="float-right">{{ $serviceCategories->links() }}</div>
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
