@php
    $supportedLocales = LaravelLocalization::getSupportedLocales()
@endphp

{{-- Extends layout --}}
@extends('admin.layout.default')

{{-- Content --}}
@section('content')

<div class="row justify-content-center">
    <div class="col-lg-8">
        @include('admin.components.success-alert')
        @include('admin.components.validation-error')

        <div class="card">
            <form method="POST" action="{{ route('admin.seo.store') }}">
                @csrf
                <input type="hidden" name="name" value="{{ $key }}">
                
                <div class="card-body">
                    {{-- title --}}
                    @foreach($supportedLocales as $localeCode => $properties)
                        <?php $slt =  'title_'.$localeCode; ?>
                        <div class="form-group row">
                            <label class="col-sm-3 col-form-label text-right">{{ trans('main.SEO') }} {{ trans('main.Title') }} ({{ $properties['native'] }})</label>
                            <div class="col-sm-9">
                                {{ Form::text($slt, $data->$slt ?? '', ['class' => 'form-control']) }}
                            </div>
                        </div>
                    @endforeach
                    {{--./ title --}}

                    {{-- keyword --}}
                    @foreach($supportedLocales as $localeCode => $properties)
                        <?php $slk =  'keyword_'.$localeCode; ?>
                        <div class="form-group row">
                            <label class="col-sm-3 col-form-label text-right">{{ trans('main.SEO') }} {{ trans('main.Keyword') }} ({{ $properties['native'] }})</label>
                            <div class="col-sm-9">
                                {{ Form::text($slk, $data->$slk ?? '', ['class' => 'form-control']) }}
                            </div>
                        </div>
                    @endforeach
                    {{--./ keyword --}}

                    {{-- description --}}
                    @foreach($supportedLocales as $localeCode => $properties)
                        <?php $sld =  'description_'.$localeCode; ?>
                        <div class="form-group row">
                            <label class="col-sm-3 col-form-label text-right">{{ trans('main.SEO') }} {{ trans('main.Description') }} ({{ $properties['native'] }})</label>
                            <div class="col-sm-9">
                                {{ Form::text($sld, $data->$sld ?? '', ['class' => 'form-control']) }}
                            </div>
                        </div>
                    @endforeach
                    {{--./ description --}}

                </div>
                
                <div class="card-footer">
                    <button type="submit" class="btn btn-success font-weight-bold mr-2 float-right">@lang('main.Submit')</button>
                    <a href="{{ route('admin.seo') }}" class="btn btn-light-success font-weight-bold">@lang('main.Back')</a>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
