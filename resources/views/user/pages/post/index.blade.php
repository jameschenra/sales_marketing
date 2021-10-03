{{-- Extends layout --}}
@extends('user.layout.default')

@section('styles')
    {{ Html::style(adminAsset('plugins/custom/datatables/datatables.bundle.css?v=7.0.5')) }}
@endsection

@section('content')
    <section class="bg-content d-flex align-items-center">
        <div class="container">
            <div class="d-flex justify-content-between">
                <h3 class="mb-10 font-weight-bold text-dark">@lang('main.My Articles')</h3>
                <a href="{{ route('user.post.create') }}"><button class="btn btn-primary">@lang('main.Post Article')</button></a>
            </div>
            

            <table class="table table-separate table-head-custom">
                <thead>
                    <tr>
                        <th style="width: 10%" class="border-top text-capitalize">#</th>
                        <th class="border-top">{{ trans('main.Title') }}</th>
                        <th class="border-top">{{ trans('main.Created At') }}</th>
                        <th class="border-top">{{ trans('main.Edit') }}</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($posts as $key => $value)
                        <tr>
                            <td class="text-truncate">{{ $key + 1 }}</td>
                            <td class="text-truncate">
                                {{ $value->title }}
                            </td>
                            <td class="text-truncate">{{ date('d-m-Y H:i', strtotime($value->created_at)) }}</td>
                            <td class="text-truncate">
                                <a href="{{ route('user.post.edit', $value->id) }}">
                                    <i class="fa fa-edit"></i>
                                </a>
                                <a href="{{ route('user.post.delete', $value->id) }}" id="btn-delete-item">
                                    <i class="fa fa-trash"></i>
                                </a>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>

            <br />
            <div class="float-right">{{ $posts->links() }}</div>
            <div class="clearfix"></div>
        </div>
    </section>
@endsection

@section('scripts')
    {{ Html::script(userAsset('libraries/readmore.js')) }}
    @include('user.include-js.readmore-js')
@endsection
