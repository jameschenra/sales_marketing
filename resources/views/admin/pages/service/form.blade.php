@php
    $supportedLocales = LaravelLocalization::getSupportedLocales();
    if (isset($service)) {
        $user_id = old('user_id', $service->user_id);
        $name_en = old('name_en', $service->name_en);
        $name_es = old('name_es', $service->name_es);
        $name_it = old('name_it', $service->name_it);
        $description_en = old('description_en', $service->description_en);
        $description_es = old('description_es', $service->description_es);
        $description_it = old('description_it', $service->description_it);
        $photo = old('photo', $service->photo);
        $photoImg = old('photo', $service->photo);
        $provideOnlineType = old('provide_online_type', $service->provide_online_type);
        $duration = old('duration', $service->duration);
        $officeId = old('office_id', $service->office_id);
        $provideType = old('provide_online_type', $service->provide_online_type);
        $price = old('price', $service->price);
    } else {
        $user_id = old('user_id');
        $name_en = old('name_en');
        $name_es = old('name_es');
        $name_it = old('name_it');
        $description_en = old('description_en');
        $description_es = old('description_es');
        $description_it = old('description_it');
        $photo = old('photo');
        $photoImg = old('photo', 'blank.png');
        $provideOnlineType = old('provide_online_type', 1);
        $duration = old('duration');
        $officeId = old('office_id');
        $provideType = old('provide_online_type');
        $price = old('price');
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
            <form method="POST" action="{{ route('admin.service.store') }}">
                @csrf
                @isset($service)
                    <input type="hidden" name="service_id" value="{{ $service->id }}" />
                @endisset

                <div class="card-body">
                    {{-- name --}}
                    @if($supportedLocales)
                        @foreach($supportedLocales as $localeCode => $properties)
                            <div class="form-group row">
                                <label class="col-sm-4 col-form-label text-right">{{ trans('main.Name').' ('.$properties['native'].')' }}</label>
                                <div class="col-sm-8">
                                    {{ Form::text('name_' . $localeCode, ${'name_' . $localeCode}, ['class' => 'form-control']) }}
                                </div>
                            </div>
                        @endforeach
                    @endif
                    {{--./ name --}}

                    {{-- description --}}
                    @if($supportedLocales)
                        @foreach($supportedLocales as $localeCode => $properties)
                        <div class="form-group row">
                            <label class="col-sm-4 col-form-label text-right">{{ trans('main.Description').' ('.$properties['native'].')' }}</label>
                            <div class="col-sm-8">
                                {{ Form::textarea('description_' . $localeCode, ${'description_' . $localeCode}, ['class' => 'form-control']) }}
                            </div>
                        </div>	
                        @endforeach
                    @endif
                    {{--./ description --}}

                    {{-- user --}}
                    <div class="form-group row">
                        <label class="col-sm-4 col-form-label text-right">@lang('main.Professional')</label>
                        <div class="col-sm-8">
                            {{ Form::select('user_id', $users->pluck('name', 'id'), NULL, array('class' => 'form-control')) }}
                        </div>
                    </div>
                    {{--./ user --}}

                    {{-- photo --}}
                    <div class="form-group row">
                        <label class="col-sm-4 col-form-label text-right">{{ trans('main.Photo') }}</label>
                        <div class="col-sm-8">
                            <div class="image-input image-input-outline" id="kt_image_1">
                                <div class="image-input-wrapper" style="background-image: url(/upload/user/{{ $photoImg }})"></div>

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

                    {{-- provide_online_type --}}
                    <div class="form-group row">
                        <label class="col-sm-4 col-form-label text-right">@lang('main.Service Available')</label>
                        <div class="col-sm-8">
                            <input type="checkbox" {{ $provideOnlineType == 1 ? 'checked' : '' }} style="margin-top: 13px"
                                name="provide_online_type" value="{{ $provideOnlineType }}">
                        </div>
                    </div>
                    {{--./ provide_online_type --}}

                    {{-- duration --}}
                    <div class="form-group row">
                        <label class="col-sm-4 col-form-label text-right">@lang('main.Duration')</label>
                        <div class="col-sm-8">
                            <input type="text" name="duration" value="{{ $duration }}" class="form-control" />
                        </div>
                    </div>
                    {{--./ duration --}}

                    {{-- office location --}}
                    <div class="form-group row">
                        <label class="col-sm-4 col-form-label text-right">@lang('main.Office')</label>
                        <div class="col-sm-8">
                            <input type="text" name="office_id" value="{{ $officeId }}" class="form-control" />
                        </div>
                    </div>
                    {{--./ office location --}}

                    {{-- available offsite --}}
                    <div class="form-group row">
                        <label class="col-sm-4 col-form-label text-right">@lang('main.Available out of office')</label>
                        <div class="col-sm-8">
                            <input type="checkbox" {{$provideType == 1 ? 'checked' : '' }} style="margin-top: 13px"
                                name="provide_online_type" value="{{ $provideType }}">
                        </div>
                    </div>
                    {{--./ available offsite --}}

                    {{-- price --}}
                    <div class="form-group row">
                        <label class="col-sm-4 col-form-label text-right">@lang('main.Price')</label>
                        <div class="col-sm-8">
                            <input type="text" name="price" value="{{ $price }}" class="form-control" placeholder="Price in Euro" required/>
                        </div>
                    </div>
                    {{--./ price --}}

                    {{-- book amount --}}
                    <div class="form-group row">
                        <label class="col-sm-4 col-form-label text-right">@lang('main.Amount of Book at a time(max:5)')</label>
                        <div class="col-sm-8">
                            <input type="text" name="book_amount" class="form-control" />
                        </div>
                    </div>
                    {{--./ book amount --}}

                    {{-- keyword --}}
                    <div class="form-group row">
                        <label class="col-sm-4 col-form-label text-right">@lang('main.Keyword')</label>
                        <div class="col-sm-8">
                            <textarea class="form-control" name="keyword" rows="3"></textarea>
                        </div>
                    </div>
                    {{--./ keyword --}}
                </div>
                
                <div class="card-footer">
                    <button type="submit" class="btn btn-success font-weight-bold mr-2 float-right">@lang('main.Submit')</button>
                    <a href="{{ route('admin.service.index') }}" class="btn btn-light-success font-weight-bold">@lang('main.Back')</a>
                </div>
            </form>
        </div>
    </div>
</div>

@endsection

{{-- Scripts Section --}}
@section('scripts')
@endsection
