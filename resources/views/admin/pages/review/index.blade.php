@extends('admin.layout.default')

@section('styles')
@endsection

{{-- Content --}}
@section('content')
    @include('admin.components.success-alert')

    <div class="card card-custom">
        <div class="card-header flex-wrap border-0 pt-6 pb-0">
            <div class="card-title w-100 justify-content-between">
                <h3 class="card-label">@lang('main.review') @lang('main.List')</h3>
            </div>
        </div>

        <div class="card-body">
            <table class="table table-bordered" id="kt_datatable">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>{{ trans('main.Store Name') }}</th>
                        <th>{{ trans('main.User') }}</th>
                        <th>{{ trans('main.Reviewer') }}</th>
                        <th>{{ trans('main.Published') }}</th>
                        <th>{{ trans('main.Created At') }}</th>
                        <th class="th-action">{{ trans('main.Action') }}</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($reviews as $key => $review)
                    <tr>
                        <td>{{ $review->id }}</td>
                        <td>{{ $review->book->service->name ?? '' }}</td>
                        <td>{{ $review->user->name .' '. $review->user->surname }}</td>
                        <td>{{ $review->reviewer->name .' '. $review->reviewer->surname }}</td>
                        <td>{{ $review->published == 0 ? 'No' : 'Yes'}}</td>                        
                        <td>{{ $review->created_at }}</td>
                        <td>
                            <a href="{{ URL::route('admin.review.edit', $review->id) }}" class="btn btn-sm btn-icon"
                                title="@lang('main.Edit')">
                                <i class="fa fa-edit"></i>
                            </a>
                            <a href="{{ URL::route('admin.review.delete', $review->id) }}" class="btn btn-sm btn-icon" id="btn-delete-item"
                                title="@lang('main.Delete')" id="btn-delete-item">
                                <i class="fa fa-trash"></i>
                            </a>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>

            <br />
            <div class="float-right">{{ $reviews->links() }}</div>
            <div class="clearfix"></div>
        </div>

    </div>
@endsection


{{-- Scripts Section --}}
@section('scripts')
@endsection
