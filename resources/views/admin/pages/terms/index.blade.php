@extends('admin.layout.default')

@section('styles')
@endsection

{{-- Content --}}
@section('content')
    @include('admin.components.success-alert')

    <div class="card card-custom">
        <div class="card-header flex-wrap border-0 pt-6 pb-0">
            <div class="card-title w-100 justify-content-between">
                <h3 class="card-label">@lang('main.Terms') @lang('main.List')</h3>
                @if (count($terms) < 1)
                    <a href="{{ route('admin.terms.create') }}" class="btn btn-primary"><i class="flaticon2-plus-1"></i>@lang('main.Create')</a>
                @endif
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
                    @foreach ($terms as $key => $term)
                    <tr>
                        <td>{{ ($key + 1) }}</td>
                        <td>{{ $term->title }}</td>
                        <td>{!! $term->content !!}</td>
                        <td>{{ $term->created_at }}</td>
                        <td>
                            <a href="{{ route('admin.terms.edit', $term->id) }}" class="btn btn-sm btn-info">
                                <span class="glyphicon glyphicon-edit"></span> {{ trans('main.Edit') }}
                            </a>
                        </td>
                        <td>
                            <a href="{{ route('admin.terms.delete', $term->id) }}" class="btn btn-sm btn-danger" id="btn-delete-item">
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
@endsection
