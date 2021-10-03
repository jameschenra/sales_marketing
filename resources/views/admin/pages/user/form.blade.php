@php
    $supportedLocales = LaravelLocalization::getSupportedLocales();
    if (isset($user)) {
        $name = old('name', $user->name);
        $namees = old('namees', $user->namees);
        $nameit = old('nameit', $user->nameit);
        $email = old('email', $user->email);
        $phone = old('phone', $user->phone);
        $photo = old('photo', $user->detail->photo);
        $photoImg = old('photo', $user->detail->photo);
    } else {
        $name = old('name');
        $namees = old('namees');
        $nameit = old('nameit');
        $email = old('email');
        $phone = old('phone');
        $photo = old('photo');
        $photoImg = old('photo', 'blank.png');
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
            <form method="POST" action="{{ route('admin.user.store') }}">
                @csrf
                @isset($user)
                    <input type="hidden" name="user_id" value="{{ $user->id }}" />
                @endisset

                <div class="card-body">
                    {{-- name --}}
                    @if($supportedLocales)
                        @foreach($supportedLocales as $localeCode => $properties)
                            <div class="form-group row">
                                <label class="col-sm-3 col-form-label text-right">{{ trans('main.Name').' ('.$properties['native'].')' }}</label>
                                <div class="col-sm-9">
                                    {{ Form::text('name'.(($localeCode == 'en')?'':$localeCode), ${'name'.(($localeCode == 'en')?'':$localeCode)}, ['class' => 'form-control']) }}
                                </div>
                            </div>
                        @endforeach
                    @endif
                    {{--./ name --}}

                    {{-- email --}}
                    <div class="form-group row">
                        <label class="col-sm-3 col-form-label text-right">{{trans('main.Email') }}</label>
                        <div class="col-sm-9">
                            <input type="email" class="form-control" name="email" value="{{ $email }}">
                        </div>
                    </div>
                    {{--./ email --}}

                    {{-- password --}}
                    <div class="form-group row">
                        <label class="col-sm-3 col-form-label text-right">{{ trans('main.Password') }}</label>
                        <div class="col-sm-9">
                            <input type="password" class="form-control" name="password">
                        </div>
                    </div>
                    {{--./ password --}}

                    {{-- phone --}}
                    <div class="form-group row">
                        <label class="col-sm-3 col-form-label text-right">{{ trans('main.Phone') }}</label>
                        <div class="col-sm-9">
                            <input type="text" class="form-control" name="phone" value="{{ $phone }}">
                        </div>
                    </div>
                    {{--./ phone --}}

                    {{-- photo --}}
                    <div class="form-group row">
                        <label class="col-sm-3 col-form-label text-right">{{ trans('main.Photo') }}</label>
                        <div class="col-sm-9">
                            <div class="image-input image-input-outline" id="photo_input">
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

                </div>
                
                <div class="card-footer">
                    <button type="submit" class="btn btn-success font-weight-bold mr-2 float-right">@lang('main.Submit')</button>
                    <a href="{{ route('admin.user.index') }}" class="btn btn-light-success font-weight-bold">@lang('main.Back')</a>
                </div>
            </form>
        </div>
    </div>
</div>


@endsection

{{-- Scripts Section --}}
@section('scripts')
<script>
    new KTImageInput('photo_input');
</script>
@endsection
