@php
    $supportedLocales = LaravelLocalization::getSupportedLocales() ?: [];
    if (isset($model)) {
        $title_en = old('title_en', $model->title_en);
        $title_es = old('title_es', $model->title_es);
        $title_it = old('title_it', $model->title_it);
        $content_en = old('content_en', $model->content_en);
        $content_es = old('content_es', $model->content_es);
        $content_it = old('content_it', $model->content_it);
        $type = old('type', $model->type);
        $photo = old('image', $model->image);
        $photoImg = old('image', $model->image);
    } else {
        $title_en = old('title_en');
        $title_es = old('title_es');
        $title_it = old('title_it');
        $content_en = old('content_en');
        $content_es = old('content_es');
        $content_it = old('content_it');
        $type = old('type');
        $photo = old('image');
        $photoImg = old('image', 'blank.png');
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
            <form method="POST" action="{{ route('admin.howitworks.store') }}" enctype="multipart/form-data">
                @csrf
                @isset($model)
                    <input type="hidden" name="model_id" value="{{ $model->id }}" />
                @endisset

                <div class="card-body">
                    {{-- type --}}
                    <div class="form-group row">
                        <label class="col-sm-3 col-form-label text-right">{{ trans('main.Type') }}</label>
                        <div class="col-sm-9">
                            <select id="type" name="type" class="form-control">
                                <option value="">{{ trans('main.Select') . " " . trans('main.Type') }}</option>
                                <option value="Professional" {{'Professional' == $type ? 'selected' : ''}}>Professional</option>
                                <option value="User" {{'User' == $type ? 'selected' : ''}}>User</option>
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

                    {{-- photo --}}
                    <div class="form-group row">
                        <label class="col-sm-3 col-form-label text-right">{{ trans('main.Featured Image') }}</label>
                        <div class="col-sm-9">
                            <div class="image-input image-input-outline" id="photo_input">
                                <div class="image-input-wrapper" style="background-image: url(/upload/howitworks/{{ $photoImg }})"></div>

                                <label class="btn btn-xs btn-icon btn-circle btn-white btn-hover-text-primary btn-shadow" data-action="change" data-toggle="tooltip" title="" data-original-title="Change avatar">
                                <i class="fa fa-pen icon-sm text-muted"></i>
                                <input type="file" name="photo" accept=".png, .jpg, .jpeg" value="{{ $photo }}"/>
                                <input type="hidden" name="photo_remove"/>
                                </label>

                                <span class="btn btn-xs btn-icon btn-circle btn-white btn-hover-text-primary btn-shadow" data-action="cancel" data-toggle="tooltip" title="Cancel avatar">
                                <i class="ki ki-bold-close icon-xs text-muted"></i>
                                </span>
                            </div>
                        </div>
                    </div>
                    {{--./ photo --}}
                </div>
                
                <div class="card-footer">
                    <button type="submit" onclick="return validate()" class="btn btn-success font-weight-bold mr-2 float-right">@lang('main.Submit')</button>
                    <a href="{{ route('admin.howitworks.index') }}" class="btn btn-light-success font-weight-bold">@lang('main.Back')</a>
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
<script>
    new KTImageInput('photo_input');
</script>
@endsection
