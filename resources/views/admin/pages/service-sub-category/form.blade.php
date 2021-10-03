@php
    $supportedLocales = LaravelLocalization::getSupportedLocales();
    if (isset($sub_category)) {
        $categoryId = old('category_id', $sub_category->category_id);
        $name_en = old('name_en', $sub_category->name_en);
        $name_es = old('name_es', $sub_category->name_es);
        $name_it = old('name_it', $sub_category->name_it);
        $description_en = old('description_en', $sub_category->description_en);
        $description_es = old('description_es', $sub_category->description_es);
        $description_it = old('description_it', $sub_category->description_it);
    } else {
        $categoryId = old('category_id');
        $name_en = old('name_en');
        $name_es = old('name_es');
        $name_it = old('name_it');
        $description_en = old('description_en');
        $description_es = old('description_es');
        $description_it = old('description_it');
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
            <form method="POST" action="{{ route('admin.svcsubcat.store') }}">
                @csrf
                @isset($sub_category)
                    <input type="hidden" name="sub_category_id" value="{{ $sub_category->id }}" />
                @endisset

                <div class="card-body">
                    {{-- category --}}
                    <div class="form-group row">
                        <label class="col-sm-3 col-form-label text-right">{{ trans('main.Category') }}</label>
                        <div class="col-md-9">
                            <select name="category_id" class="form-control">
                                @foreach ($categories as $category)
                                    <option value="{{$category->id}}" {{$category->id == $categoryId ? 'selected':''}}>{{ $category->name }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    {{--./ category --}}

                    {{-- name --}}
                    @if($supportedLocales)
                        @foreach($supportedLocales as $localeCode => $properties)
                            <div class="form-group row">
                                <label class="col-sm-3 col-form-label text-right">{{ trans('main.Name').' ('.$properties['native'].')' }}</label>
                                <div class="col-sm-9">
                                    {{ Form::text('name_' . $localeCode, ${'name_' . $localeCode}, ['class' => 'form-control']) }}
                                </div>
                            </div>
                        @endforeach
                    @endif
                    {{--./ name --}}

                    @if($supportedLocales)
                        @foreach($supportedLocales as $localeCode => $properties)
                        <div class="form-group row">
                            <label class="col-sm-3 col-form-label text-right">{{ trans('main.Description').' ('.$properties['native'].')' }}</label>
                            <div class="col-sm-9">
                                {{ Form::textarea('description_' . $localeCode, ${'description_' . $localeCode}, ['class' => 'form-control']) }}
                            </div>
                        </div>	
                        @endforeach
                    @endif
                </div>
                
                <div class="card-footer">
                    <button type="submit" class="btn btn-success font-weight-bold mr-2 float-right">@lang('main.Submit')</button>
                    <a href="{{ route('admin.svcsubcat.index') }}" class="btn btn-light-success font-weight-bold">@lang('main.Back')</a>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection