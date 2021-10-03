@php
    $supportedLocales = LaravelLocalization::getSupportedLocales() ?: [];
    if (isset($type)) {
        $name_en = old('name_en', $type->name_en);
        $name_es = old('name_es', $type->name_es);
        $name_it = old('name_it', $type->name_it);
    } else {
        $name_en = old('name_en');
        $name_es = old('name_es');
        $name_it = old('name_it');
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
            <form method="POST" action="{{ route('admin.helptype.store') }}">
                @csrf
                @isset($type)
                    <input type="hidden" name="type_id" value="{{ $type->id }}" />
                @endisset

                <div class="card-body">
                    {{-- type --}}
                    @foreach($supportedLocales as $localeCode => $properties)
                        <div class="form-group row">
                            <label class="col-sm-3 col-form-label text-right">{{ trans('main.Type').' ('.$properties['native'].')' }}</label>
                            <div class="col-sm-9">
                                {{ Form::text('name_' . $localeCode, ${'name_' . $localeCode}, ['class' => 'form-control']) }}
                            </div>
                        </div>
                    @endforeach
                    {{--./ type --}}
                </div>
                
                <div class="card-footer">
                    <button type="submit" onclick="return validate()" class="btn btn-success font-weight-bold mr-2 float-right">@lang('main.Submit')</button>
                    <a href="{{ route('admin.helptype.index') }}" class="btn btn-light-success font-weight-bold">@lang('main.Back')</a>
                </div>
            </form>
        </div>
    </div>
</div>


@endsection

{{-- Scripts Section --}}
@section('scripts')
@endsection
