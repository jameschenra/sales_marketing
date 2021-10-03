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
                <h3 class="card-label">@lang('main.Users')</h3>
                <a href="{{ route('admin.user.create') }}" class="btn btn-primary"><i class="flaticon2-plus-1"></i>@lang('main.Create')</a>
            </div>
        </div>

        <div class="card-body">
            <table class="table table-bordered table-hover" id="kt_datatable">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>{{ trans('main.Name') }}</th>
                        <th>{{ trans('main.Email') }}</th>
                        <th>{{ trans('main.Phone') }}</th>
                        <th>{{ trans('main.Created At') }}</th>
                        <th>{{ trans('main.Status') }}</th>
                        <th>{{ trans('main.action') }}</th>
                        <th class="th-action">{{ trans('main.Edit') }}</th>
                        <th class="th-action">{{ trans('main.Delete') }}</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($users as $key => $user)
                        <tr>
                            <td>{{ ((Request::has('page') ? Request::get('page') : 1) - 1 ) * PAGINATION_SIZE + ($key + 1) }}</td>
                            <td>{{ $user->full_name }}</td>
                            <td>{{ $user->email }}</td>
                            <td>{{ $user->phone }}</td>
                            <td>{{ $user->created_at }}</td>
                            <td>
                                @if($user->is_active == 1)
                                    Active
                                @else
                                    Inactive
                                @endif
                            </td>
                            <td>
                                @if($user->is_suspend == 1)
                                    <a title="Click here to unblock" href="{{ route('admin.user.unblock', $user->id) }}" class="btn btn-sm btn-info">
                                        <span class="fa fa-times"></span>
                                    </a>
                                @else
                                    <a title="Click here to block" href="{{ route('admin.user.block', $user->id) }}" class="btn btn-sm btn-info">
                                        <span class="fa fa-check"></span>
                                    </a>
                                @endif
                            </td>
                            <td>
                                <a href="{{ URL::route('admin.user.edit', $user->id) }}" class="btn btn-sm btn-info">
                                    <span class="glyphicon glyphicon-edit"></span> {{ trans('main.Edit') }}
                                </a>
                            </td>
                            <td>
                                <a href="{{ URL::route('admin.user.delete', $user->id) }}" class="btn btn-sm btn-danger" id="btn-delete-item">
                                    <span class="glyphicon glyphicon-trash"></span> {{ trans('main.Delete') }}
                                </a>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>

        </div>

    </div>
@endsection


{{-- Scripts Section --}}
@section('scripts')
    {{-- vendors --}}
    <script src="{{ adminAsset('plugins/custom/datatables/datatables.bundle.js') }}" type="text/javascript"></script>

    {{-- page scripts --}}
@endsection
