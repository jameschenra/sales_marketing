@extends('admin.layout.default')

@section('styles')
@endsection

{{-- Content --}}
@section('content')
    @include('admin.components.success-alert')

    <div class="card card-custom">
        <div class="card-header flex-wrap border-0 pt-6 pb-0">
            <div class="card-title">
                <h3 class="card-label">@lang('main.SEO') @lang('main.List')</h3>
            </div>
        </div>

        <div class="card-body">
            <table class="table table-bordered table-striped" id="kt_datatable">
                <thead>
                    <tr>
                        <th>{{ trans('main.Name') }}</th>
                        <th class="th-action">{{ trans('main.Edit') }}</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($sections as $key => $section)
                        <tr role="row" class="odd">
                            <td>{{ $section['title'] }}</td>
                            <td>
                                <a href="{{ route('admin.seo.view', [$key]) }}" class="btn btn-sm btn-info">
                                    <span class="glyphicon glyphicon-edit"></span> {{ trans('main.Edit') }}
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
