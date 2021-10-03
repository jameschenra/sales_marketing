@php
    $supportedLocales = LaravelLocalization::getSupportedLocales() ?: [];
    if (isset($help)) {
        $helpType = old('help_type_id', $help->help_type_id);
        $title_en = old('title_en', $help->title_en);
        $title_es = old('title_es', $help->title_es);
        $title_it = old('title_it', $help->title_it);
        $content_en = old('content_en', $help->content_en);
        $content_es = old('content_es', $help->content_es);
        $content_it = old('content_it', $help->content_it);
        $status = old('status', $help->status);
    } else {
        $helpType = old('help_type_id');
        $title_en = old('title_en');
        $title_es = old('title_es');
        $title_it = old('title_it');
        $content_en = old('content_en');
        $content_es = old('content_es');
        $content_it = old('content_it');
        $status = old('status');
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
            <form method="POST" action="{{ route('admin.help.store') }}">
                @csrf
                @isset($help)
                    <input type="hidden" name="help_id" value="{{ $help->id }}" />
                @endisset

                <div class="card-body">
                    {{-- type --}}
                    <div class="form-group row">
                        <label class="col-sm-3 col-form-label text-right">{{ trans('main.Type') }}</label>
                        <div class="col-sm-9">
                            <select id="help_type_id" name="help_type_id" class="form-control">
                                @foreach ($types as $type)
                                    <option value="{{ $type->id }}" {{$type->id == $helpType ? 'selected' : ''}}>{{ $type->name }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    {{--./ type --}}

                    {{-- title --}}
                    @foreach($supportedLocales as $localeCode => $properties)
                        <div class="form-group row">
                            <label class="col-sm-3 col-form-label text-right">{{ trans('main.Title').' ('.$properties['native'].')' }}</label>
                            <div class="col-sm-9">
                                {{ Form::text('title_' . $localeCode, ${'title_' . $localeCode}, ['class' => 'form-control']) }}
                            </div>
                        </div>
                    @endforeach
                    {{--./ title --}}

                    {{-- content --}}
                    @foreach($supportedLocales as $localeCode => $properties)
                        <div class="form-group row">
                            <label class="col-sm-3 col-form-label text-right">{{ trans('main.Content').' ('.$properties['native'].')' }}</label>
                            <div class="col-sm-9">
                                <textarea name="{{ 'content_' . $localeCode }}" class="content-ck-editor">
                                    {!! ${'content_' . $localeCode} !!}
                                </textarea>
                            </div>
                        </div>
                    @endforeach
                    {{--./ content --}}
                </div>
                
                <div class="card-footer">
                    <button type="submit" onclick="return validate()" class="btn btn-success font-weight-bold mr-2 float-right">@lang('main.Submit')</button>
                    <a href="{{ route('admin.help.index') }}" class="btn btn-light-success font-weight-bold">@lang('main.Back')</a>
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
