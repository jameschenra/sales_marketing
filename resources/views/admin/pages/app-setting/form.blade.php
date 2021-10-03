@php
    $supportedLocales = LaravelLocalization::getSupportedLocales() ?: [];
@endphp

{{-- Extends layout --}}
@extends('admin.layout.default')

{{-- Content --}}
@section('content')

<div class="row justify-content-center">
    <div class="col-lg-8">
        @include('admin.components.validation-error')

        <div class="card">
            <form method="POST" action="{{ route('admin.setting.store') }}">
                @csrf

                <div class="card-body">
                    @foreach ($website_settings as $website_setting)
                        <div class="form-group row">
                            <label class="col-sm-3 form-col-label">{{ trans($website_setting->name_trans) }}</label>
                            <div class="col-sm-9">
                                @if($website_setting->form_type == 'select')
                                    <select name="{{ $website_setting->name }}" class="form-control">
                                        @foreach ($website_setting->selectOptions as $option)
                                            <option value="{{ $option->value }}" 
                                                <?= $website_setting->value == $option->value ? "selected" : '';?> >
                                                {{ trans($option->name_trans) }}
                                            </option>
                                        @endforeach
                                    </select>
                                @elseif($website_setting->form_type == 'input')
                                    <input type="{{ $website_setting->input_type }}" class="form-control"
                                        name="{{ $website_setting->name }}" value="{{ $website_setting->value }}" />
                                @endif

                                @if ($website_setting->description)
                                    <small class="form-text text-muted">{{ trans($website_setting->description) }}</small>
                                @endif
                            </div>
                        </div>
                    @endforeach
                </div>
                
                <div class="card-footer text-right">
                    <button type="submit" onclick="return validate()" class="btn btn-success font-weight-bold mr-2">@lang('main.Submit')</button>
                </div>
            </form>
        </div>
    </div>
</div>


@endsection

{{-- Scripts Section --}}
@section('scripts')
    <script src="{{ adminAsset('plugins/custom/ckeditor/ckeditor-classic.bundle.js?v=7.0.5') }}" type="text/javascript"></script>
    <script src="{{ adminAsset('custom/ck-editor.js') }}" type="text/javascript"></script>
@endsection
