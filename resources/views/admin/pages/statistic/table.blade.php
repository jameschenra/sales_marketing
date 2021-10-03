@extends('admin.layout.default')

@section('styles')
@endsection

{{-- Content --}}
@section('content')
    @include('admin.components.success-alert')

    <div class="card card-custom">
        <div class="card-header flex-wrap border-0 pt-6 pb-0">
            <div class="card-title w-100 justify-content-between">
                <h3 class="card-label">{{ $table_name }}</h3>
                <a href="{{ route('admin.statistic.index') }}" class="btn btn-warning">@lang('main.Back')</a>
            </div>
        </div>

        <div class="card-body">
            <table class="table table-bordered" id="kt_datatable">
                <thead>
                    <tr>
                        <th>{{ trans('main.Name') }}</th>
                        <th>{{ trans('main.Email') }}</th>
                        <th>{{ trans('main.Sum') }}</th>
                        <th>{{ trans('main.Created At') }}</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($user_statistics as $user_statistic)
                        <tr>
                            @switch($stat_id)
                            @case(2)
                                <td>{{ $user_statistic->user['name'] }}</td>                       
                                <td>{{ $user_statistic->user['email'] }}</td>   
                                @break
                            @default
                                <td>{{ $user_statistic->name }}</td>                       
                                <td>{{ $user_statistic->email }}</td> 
                            @endswitch

                            <td>{{ $user_statistic->amount }}</td>
                            <td>{{ $user_statistic->created_at }}</td>
                                    
                        </tr>
                    @endforeach
                </tbody>
            </table>

            <br />
            <div class="float-right">{{ $user_statistics->links() }}</div>
            <div class="clearfix"></div>
        </div>

    </div>
@endsection


{{-- Scripts Section --}}
@section('scripts')
@endsection
