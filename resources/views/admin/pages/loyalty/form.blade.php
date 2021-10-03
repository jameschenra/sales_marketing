@php
    $supportedLocales = LaravelLocalization::getSupportedLocales() ?: [];
    if (isset($loyalty)) {
        $name_en = old('name_en', $loyalty->name_en);
        $name_es = old('name_es', $loyalty->name_es);
        $name_it = old('name_it', $loyalty->name_it);
        $description_en = old('description_en', $loyalty->description_en);
        $description_es = old('description_es', $loyalty->description_es);
        $description_it = old('description_it', $loyalty->description_it);
        $userId = old('user_id', $loyalty->user_id);
        $countStamp = old('count_stamp', $loyalty->count_stamp);
    } else {
        $name_en = old('name_en');
        $name_es = old('name_es');
        $name_it = old('name_it');
        $description_en = old('description_en');
        $description_es = old('description_es');
        $description_it = old('description_it');
        $userId = old('user_id');
        $countStamp = old('count_stamp');
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
            <form method="POST" action="{{ route('admin.loyalty.store') }}">
                @csrf
                @isset($loyalty)
                    <input type="hidden" name="loyalty_id" value="{{ $loyalty->id }}" />
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

                    {{-- description --}}
                    @foreach($supportedLocales as $localeCode => $properties)
                        <div class="form-group row">
                            <label class="col-sm-3 col-form-label text-right">{{ trans('main.Description').' ('.$properties['native'].')' }}</label>
                            <div class="col-sm-9">
                                {{ Form::text('description_' . $localeCode, ${'description_' . $localeCode}, ['class' => 'form-control']) }}
                            </div>
                        </div>
                    @endforeach
                    {{--./ description --}}

                    {{-- user --}}
                    <div class="form-group row">
                        <label class="col-sm-3 col-form-label text-right">{{ trans('main.User') }}</label>
                        <div class="col-sm-9">
                            {{ Form::select('user_id', $users->pluck('name', 'id'), $userId, ['class' => 'form-control']) }}
                        </div>
                    </div>
                    {{--./ user --}}

                    {{-- count stamp --}}
                    <div class="form-group row">
                        <label class="col-sm-3 col-form-label text-right">{{ trans('main.Count Stamp') }}</label>
                        <div class="col-sm-9">
                            {{ Form::text('count_stamp', $countStamp, ['class' => 'form-control']) }}
                        </div>
                    </div>
                    {{--./ count stamp --}}
                </div>
                
                <div class="card-footer">
                    <button type="submit" class="btn btn-success font-weight-bold mr-2 float-right">@lang('main.Submit')</button>
                    <a href="{{ route('admin.loyalty.index') }}" class="btn btn-light-success font-weight-bold">@lang('main.Back')</a>
                </div>
            </form>
        </div>
    </div>
</div>


@endsection

{{-- Scripts Section --}}
@section('scripts')
@endsection
