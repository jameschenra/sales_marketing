@extends('admin.layout.default')

@section('styles')
@endsection

{{-- Content --}}
@section('content')
    @include('admin.components.success-alert')

    <div class="card card-custom">
        <div class="card-header flex-wrap border-0 pt-6 pb-0">
            <div class="card-title w-100 justify-content-between">
                <h3 class="card-label">@lang('main.Loyalty') @lang('main.List')</h3>
                <a href="{{ route('admin.loyalty.create') }}" class="btn btn-primary"><i class="flaticon2-plus-1"></i>@lang('main.Create')</a>
            </div>
        </div>

        <div class="card-body">
            <table class="table table-bordered" id="kt_datatable">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>{{ trans('main.Name') }}</th>
                        <th>{{ trans('main.Company') }}</th>
                        <th>{{ trans('main.Description') }}</th>
                        <th>{{ trans('main.Count Stamp') }}</th>
                        <th>{{ trans('main.Created At') }}</th>
                        <th class="th-action">{{ trans('main.Edit') }}</th>
                        <th class="th-action">{{ trans('main.Delete') }}</th>
                    </tr>
                </thead>
                <tbody>
                   @foreach ($loyalties as $key => $loyalty)
                    <tr>
                        <td>{{ ((Request::has('page') ? Request::get('page') : 1) - 1 ) * PAGINATION_SIZE + ($key + 1) }}</td>
                        <td>{{ $loyalty->name }}</td>
                        <td>{{ $loyalty->user->name }}</td>
                        <td>{{ $loyalty->description }}</td>
                        <td>{{ $loyalty->count_stamp }}</td>
                        <td>{{ $loyalty->created_at }}</td>
                        <td>
                            <a href="{{ route('admin.loyalty.edit', $loyalty->id) }}" class="btn btn-sm btn-info">
                                <span class="glyphicon glyphicon-edit"></span> {{ trans('main.Edit') }}
                            </a>
                        </td>
                        <td>
                            <a href="{{ route('admin.loyalty.delete', $loyalty->id) }}" class="btn btn-sm btn-danger" id="btn-delete-item">
                                <span class="glyphicon glyphicon-trash"></span> {{ trans('main.Delete') }}
                            </a>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>

            <br />
            <div class="float-right">{{ $loyalties->links() }}</div>
            <div class="clearfix"></div>
        </div>

    </div>
@endsection


{{-- Scripts Section --}}
@section('scripts')
@endsection
