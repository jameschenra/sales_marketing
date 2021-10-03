@php
    $supportedLocales = LaravelLocalization::getSupportedLocales() ?: [];
    if (isset($term)) {
        $title_en = old('title_en', $term->title_en);
        $title_es = old('title_es', $term->title_es);
        $title_it = old('title_it', $term->title_it);
        $content_en = old('content_en', $term->content_en);
        $content_es = old('content_es', $term->content_es);
        $content_it = old('content_it', $term->content_it);
        $status = old('status', $term->status);
    } else {
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
            <form method="POST" action="{{ route('admin.terms.store') }}">
                @csrf
                @isset($term)
                    <input type="hidden" name="term_id" value="{{ $term->id }}" />
                @endisset

                <div class="card-body">
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
                                {{ Form::textArea('content_' . $localeCode, ${'content_' . $localeCode}, ['class' => 'form-control']) }}
                            </div>
                        </div>
                    @endforeach
                    {{--./ content --}}
                </div>
                
                <div class="card-footer">
                    <button type="submit" onclick="return validate()" class="btn btn-success font-weight-bold mr-2 float-right">@lang('main.Submit')</button>
                    <a href="{{ route('admin.terms.index') }}" class="btn btn-light-success font-weight-bold">@lang('main.Back')</a>
                </div>
            </form>
        </div>
    </div>
</div>


@endsection

{{-- Scripts Section --}}
@section('scripts')
@endsection
