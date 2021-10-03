@php
    $supportedLocales = LaravelLocalization::getSupportedLocales() ?: [];
    if (isset($review)) {
        $content = old('review', $review->review);
        $is_published = old('is_published', $review->is_published);
        $created_at = old('created_at', $review->created_at);
        $updated_at = old('updated_at', $review->updated_at);
        
    } else {
        $content = old('review');
        $is_published = old('is_published');
        $created_at = old('created_at');
        $updated_at = old('updated_at');
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
            <form method="POST" action="{{ route('admin.review.store') }}">
                @csrf
                @isset($review)
                    <input type="hidden" name="review_id" value="{{ $review->id }}" />
                @endisset

                <div class="card-body">
                    {{-- content --}}
                    <div class="form-group row">
                        <label class="col-sm-3 col-form-label text-right">{{ trans('main.Content') }}</label>
                        <div class="col-sm-9">
                            {{ Form::textarea('review', $content, ['class' => 'form-control']) }}
                        </div>
                    </div>
                    {{--./ content --}}

                    {{-- is published --}}
                    <div class="form-group row">
                        <label class="col-sm-3 col-form-label text-right">{{ trans('main.Published') }}</label>
                        <div class="col-sm-9">
                            <select name="is_published" class="form-control">
                                <option value="1" {{ $is_published == 1 ? 'selected' : '' }}>@lang('main.Yes')</option>
                                <option value="0" {{ $is_published != 1 ? 'selected' : '' }}>@lang('main.No')</option>
                            </select>
                        </div>
                    </div>
                    {{--./ is published --}}

                    {{-- created_at --}}
                    <div class="form-group row">
                        <label class="col-sm-3 col-form-label text-right">{{ trans('main.Created At') }}</label>
                        <div class="col-sm-9">
                            <label class="control-label mt-3">{{ $created_at }}</label>
                        </div>
                    </div>
                    {{--./ created_at --}}

                    {{-- updated_at --}}
                    <div class="form-group row">
                        <label class="col-sm-3 col-form-label text-right">{{ trans('main.Updated At') }}</label>
                        <div class="col-sm-9">
                            <label class="control-label mt-3">{{ $updated_at }}</label>
                        </div>
                    </div>
                    {{--./ updated_at --}}
                </div>
                
                <div class="card-footer">
                    <button type="submit" onclick="return validate()" class="btn btn-success font-weight-bold mr-2 float-right">@lang('main.Submit')</button>
                    <a href="{{ route('admin.review.index') }}" class="btn btn-light-success font-weight-bold">@lang('main.Back')</a>
                </div>
            </form>
        </div>
    </div>
</div>

@endsection

{{-- Scripts Section --}}
@section('scripts')
@endsection
