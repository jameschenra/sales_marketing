@php
    $supportedLocales = LaravelLocalization::getSupportedLocales() ?: [];
    if (isset($plan)) {
        $name_en = old('name_en', $plan->name_en);
        $name_es = old('name_es', $plan->name_es);
        $name_it = old('name_it', $plan->name_it);
        $type = old('type', $plan->type);
        $price = old('price', $plan->price);
        $code = old('code', $plan->code);
    } else {
        $name_en = old('name_en');
        $name_es = old('name_es');
        $name_it = old('name_it');
        $type = old('type');
        $price = old('price');
        $code = old('code');
    }
@endphp

{{-- Extends layout --}}
@extends('admin.layout.default')

{{-- Content --}}
@section('content')

<div class="row justify-content-center">
    <div class="col-lg-8">
        @include('admin.components.validation-error')

        <div class="card">
            <form method="POST" action="{{ route('admin.plan.store') }}">
                @csrf
                @isset($plan)
                    <input type="hidden" name="plan_id" value="{{ $plan->id }}" />
                @endisset

                <div class="card-body">
                    {{-- name --}}
                    @foreach($supportedLocales as $localeCode => $properties)
                        <div class="form-group row">
                            <label class="col-sm-3 col-form-label text-right">{{ trans('main.Name').' ('.$properties['native'].')' }}</label>
                            <div class="col-sm-9">
                                {{ Form::text('name_' . $localeCode, ${'name_' . $localeCode}, ['class' => 'form-control']) }}
                            </div>
                        </div>
                    @endforeach
                    {{--./ name --}}

                    {{-- price --}}
                    <div class="form-group row">
                        <label class="col-sm-3 col-form-label text-right">{{ trans('main.Price') }}</label>
                        <div class="col-sm-9">
                            {{ Form::text('price', $price, ['class' => 'form-control']) }}
                        </div>
                    </div>
                    {{--./ price --}}

                    {{-- code --}}
                    <div class="form-group row">
                        <label class="col-sm-3 col-form-label text-right">{{ trans('main.Code') }}</label>
                        <div class="col-sm-9">
                            {{ Form::text('code', $code, ['class' => 'form-control']) }}
                        </div>
                    </div>
                    {{--./ code --}}

                    {{-- type --}}
                    <div class="form-group row">
                        <label class="col-sm-3 col-form-label text-right">{{ trans('main.Type') }}</label>
                        <div class="col-sm-9">
                            <select id="type" name="type" class="form-control">
                                <option value="py" {{ $type == 'py' ? 'selected' : '' }}>{{ trans('main.Per Year') }}</option>
                                <option value="ps" {{ $type == 'ps' ? 'selected' : '' }}>{{ trans('main.Per Service') }}</option>
                            </select>
                        </div>
                    </div>
                    {{--./ type --}}
                </div>
                
                <div class="card-footer">
                    <button type="submit" onclick="return validate()" class="btn btn-success font-weight-bold mr-2 float-right">@lang('main.Submit')</button>
                    <a href="{{ route('admin.plan.index') }}" class="btn btn-light-success font-weight-bold">@lang('main.Back')</a>
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
