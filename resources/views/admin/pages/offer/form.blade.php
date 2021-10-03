@php
    $supportedLocales = LaravelLocalization::getSupportedLocales() ?: [];
    if (isset($offer)) {
        $name_en = old('name_en', $offer->name_en);
        $name_es = old('name_es', $offer->name_es);
        $name_it = old('name_it', $offer->name_it);
        $description_en = old('description_en', $offer->description_en);
        $description_es = old('description_es', $offer->description_es);
        $description_it = old('description_it', $offer->description_it);
        $userId = old('user_id', $offer->user_id);
        $price = old('price', $offer->price);
        $expireAt = old('expire_at', $offer->expire_at);
        $received = old('received', $offer->received);
    } else {
        $name_en = old('name_en');
        $name_es = old('name_es');
        $name_it = old('name_it');
        $description_en = old('description_en');
        $description_es = old('description_es');
        $description_it = old('description_it');
        $userId = old('user_id');
        $price = old('price');
        $expireAt = old('expire_at');
        $received = old('received');
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
            <form method="POST" action="{{ route('admin.offer.store') }}">
                @csrf
                @isset($offer)
                    <input type="hidden" name="offer_id" value="{{ $offer->id }}" />
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

                    {{-- price --}}
                    <div class="form-group row">
                        <label class="col-sm-3 col-form-label text-right">{{ trans('main.Price') }}</label>
                        <div class="col-sm-9">
                            {{ Form::text('price', $price, ['class' => 'form-control']) }}
                        </div>
                    </div>
                    {{--./ price --}}

                    {{-- expire at --}}
                    <div class="form-group row">
                        <label class="col-sm-3 col-form-label text-right">{{ trans('main.Expire At') }}</label>
                        <div class="col-sm-9">
                            <div class="input-group date">
                                <input id="expire-date" type="text" name="expire_at" value="{{ $expireAt }}" class="form-control" readonly  placeholder="{{ trans('main.Select date') }}"/>
                                <div class="input-group-append">
                                    <span class="input-group-text">
                                        <i class="la la-calendar-check-o"></i>
                                    </span>
                                </div>
                            </div>
                            
                        </div>
                    </div>
                    {{--./ expire at --}}

                    {{-- received --}}
                    <div class="form-group row">
                        <label class="col-sm-3 col-form-label text-right">{{ trans('main.Received') }}</label>
                        <div class="col-sm-9">
                            {{ Form::text('received', $received, ['class' => 'form-control']) }}
                        </div>
                    </div>
                    {{--./ received --}}
                </div>
                
                <div class="card-footer">
                    <button type="submit" class="btn btn-success font-weight-bold mr-2 float-right">@lang('main.Submit')</button>
                    <a href="{{ route('admin.offer.index') }}" class="btn btn-light-success font-weight-bold">@lang('main.Back')</a>
                </div>
            </form>
        </div>
    </div>
</div>


@endsection

{{-- Scripts Section --}}
@section('scripts')
<script>
    $(function(){
        $('#expire-date').datepicker();
    });
</script>
@endsection
