@extends('admin.layout.default')

@section('styles')
@endsection

{{-- Content --}}
@section('content')
    @include('admin.components.success-alert')

    <div class="card card-custom">
        <div class="card-header flex-wrap border-0 pt-6 pb-0">
            <div class="card-title w-100 justify-content-between">
                <h3 class="card-label">@lang('main.World of Professions') @lang('main.List')</h3>
                <a href="{{ route('admin.worldofprofession.create') }}" class="btn btn-primary"><i class="flaticon2-plus-1"></i>@lang('main.Create')</a>
            </div>
        </div>

        <div class="card-body">
            <table class="table table-bordered" id="kt_datatable">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>{{ trans('main.Title') }}</th>
                        <th>{{ trans('main.Content') }}</th>
                        <th>{{ trans('main.Created At') }}</th>
                        <th class="th-action">{{ trans('main.Edit') }}</th>
                        <th class="th-action">{{ trans('main.Delete') }}</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($world_of_professionals as $key => $item)
                    <tr>
                        <td>{{ ($key + 1) }}</td>
                        <td>{{ $item->title }}</td>
                        <td>{{ substr(strip_tags(html_entity_decode($item->content)), 0, 300) }}</td>
                        <td>{{ $item->created_at }}</td>
                        <td>
                            <a href="{{ route('admin.worldofprofession.edit', $item->id) }}" class="btn btn-sm btn-info">
                                <span class="glyphicon glyphicon-edit"></span> {{ trans('main.Edit') }}
                            </a>
                        </td>
                        <td>
                            <a href="{{ route('admin.worldofprofession.delete', $item->id) }}" class="btn btn-sm btn-danger" id="btn-delete-item">
                                <span class="glyphicon glyphicon-trash"></span> {{ trans('main.Delete') }}
                            </a>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>

            <br />
            <div class="float-right">{{ $world_of_professionals->links() }}</div>
            <div class="clearfix"></div>
        </div>
    </div>
@endsection


{{-- Scripts Section --}}
@section('scripts')
@endsection
