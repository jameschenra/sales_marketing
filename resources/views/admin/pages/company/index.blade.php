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
                <h3 class="card-label">@lang('main.Professional')</h3>
                <a href="{{ route('admin.company.create') }}" class="btn btn-primary"><i class="flaticon2-plus-1"></i>@lang('main.Create')</a>
            </div>
        </div>

        <div class="card-body">
            <table class="table table-bordered table-striped" id="kt_datatable">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>{{ trans('main.Name') }}</th>
                        <th>{{ trans('main.Email') }}</th>
                        <th>{{ trans('main.Phone') }}</th>
                        <th>{{ trans('main.VAT ID') }}</th>
                        <th>{{ trans('main.Email') }}/{{ trans('main.SMS') }}</th>
                        <th>{{ trans('main.Status') }}</th>
                        <th>{{ trans('main.Phone') }}</th>
                        <th>{{ trans('main.Payment') }}</th>
                        <th>{{ trans('main.action') }}</th>
                        <th class="th-action">{{ trans('main.Edit') }}</th>
                        <th class="th-action">{{ trans('main.Feedback') }}</th>
                        <th class="th-action">{{ trans('main.Delete') }}</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($companies as $key => $company)
                        <tr>
                            <td>{{ ((Request::has('page') ? Request::get('page') : 1) - 1 ) * PAGINATION_SIZE + ($key + 1) }}</td>
                            <td>{{ $company->name }}</td>
                            <td>{{ $company->email }}</td>
                            <td>{{ $company->phone }}</td>
                            <td>{{ $company->vat_id }}</td>
                            <td>{{ $company->count_email . "/" . $company->count_sms }}</td>
                            <td>
                                @if($company->is_active == 1)
                                    Active
                                @else
                                    Inactive
                                @endif
                            </td>
                            <td><input type="checkbox" class="verified-box" c_type="phone_verified" main="{{ $company->id }}" <?php if($company->phone_verified == 1) echo "checked"; ?> ></td>
                            <td><input type="checkbox" class="verified-box" c_type="payment_verified" main="{{ $company->id }}" <?php if($company->payment_verified == 1) echo "checked"; ?> ></td>
                            <td>
                                @if($company->is_suspend == 1)
                                    <a title="Click here to unblock" href="{{ URL::route('admin.company.unblock', $company->id) }}" class="btn btn-sm btn-info">
                                        <span class="fa fa-times"></span>
                                    </a>
                                @else
                                    <a title="Click here to block" href="{{ URL::route('admin.company.block', $company->id) }}" class="btn btn-sm btn-info">
                                        <span class="fa fa-check"></span>
                                    </a>
                                @endif
                            </td>
                            <td>
                                <a href="{{ route('admin.company.edit', $company->id) }}" class="btn btn-sm btn-info">
                                    <span class="fa fa-edit"></span>
                                </a>
                            </td>
                            <td>
                                <a href="{{ route('admin.company.feedback', $company->id) }}" class="btn btn-sm btn-info">
                                    <i class="fa fa-star"></i>
                                </a>
                            </td>                        
                            <td>
                                <a href="{{ route('admin.company.delete', $company->id) }}" class="btn btn-sm btn-danger" id="btn-delete-item">
                                    <span class="fa fa-trash"></span>
                                </a>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>

            <br />
            <div class="float-right">{{ $companies->links() }}</div>
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
