@php
    $supportedLocales = LaravelLocalization::getSupportedLocales();
    if (isset($service_category)) {
        $name_en = old('name_en', $service_category->name_en);
        $name_es = old('name_es', $service_category->name_es);
        $name_it = old('name_it', $service_category->name_it);
        $icon = old('icon', $service_category->icon);
        $description_en = old('description_en', $service_category->description_en);
        $description_es = old('description_es', $service_category->description_es);
        $description_it = old('description_it', $service_category->description_it);
    } else {
        $name_en = old('name_en');
        $name_es = old('name_es');
        $name_it = old('name_it');
        $icon = old('icon', DEFAULT_ICON);
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
            <form method="POST" action="{{ route('admin.svccat.store') }}">
                @csrf
                @isset($service_category)
                    <input type="hidden" name="service_category_id" value="{{ $service_category->id }}" />
                @endisset

                <div class="card-body">
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

                    <div class="form-group row">
                        <label class="col-sm-3 col-form-label text-right">{{ trans('main.Icon') }}</label>
                        <div class="col-sm-9">
                            <input type="hidden" class="form-control" name="icon" id="js-hidden-icon" value="{{ DEFAULT_ICON }}">
                            <a class="btn btn-default" data-toggle="modal" href="#js-modal-icon" id="js-a-icon">
                                <i class="{{ $icon }}"></i> 
                            </a>
                            <span class="color-gray-normal"><i>&nbsp;&nbsp;&nbsp;( {{ trans('main.Click the button to change the Icon') }} )</i></span>
                        </div>
                    </div>

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
                    <a href="{{ route('admin.svccat.index') }}" class="btn btn-light-success font-weight-bold">@lang('main.Back')</a>
                </div>
            </form>
        </div>
    </div>
</div>

<div class="modal fade bs-modal-lg" id="js-modal-icon" tabindex="-1" role="dialog" aria-hidden="true" style="display: none;">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true"></button>
                <h4 class="modal-title">{{ trans('main.Please select the Icon') }}</h4>
            </div>
            <div class="modal-body">
                @foreach ($icons as $key => $value)
                    <button class="btn btn-default js-btn-icon" style="margin-top: 1px;"><i class="{{ $value }}"></i></button>
                @endforeach
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal">{{ trans('main.Close') }}</button>
                <button type="button" class="btn blue" id="js-btn-select" data-dismiss="modal">{{ trans('main.Select') }}</button>
            </div>
        </div>
    </div>
</div>
@endsection

{{-- Scripts Section --}}
@section('scripts')
<script>
    $(document).ready(function() {
        $("button.js-btn-icon").click(function() {
            $("button.js-btn-icon").removeClass("red");
            $("button.js-btn-icon").addClass("btn-default");
            $(this).addClass('red');
            $(this).removeClass('btn-default');
        });
        $("button#js-btn-select").click(function() {
            if ($("button.js-btn-icon.red").length > 0) {
                var icon = $("button.js-btn-icon.red").find("i").attr('class');
                $("#js-hidden-icon").val(icon);
                $("#js-a-icon").find("i").attr('class', icon);
                $("#js-modal-icon").modal('toggle');
            } else {
                swal.fire({text: '{{ trans("main.Please select the Icon") }}'});;
            }
        });

    });
</script>
@endsection
