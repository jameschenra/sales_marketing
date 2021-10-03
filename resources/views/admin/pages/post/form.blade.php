@php
    $supportedLocales = LaravelLocalization::getSupportedLocales() ?: [];
    if (isset($post)) {
        $title_en = old('title_en', $post->title_en);
        $title_es = old('title_es', $post->title_es);
        $title_it = old('title_it', $post->title_it);
        $content_en = old('content_en', $post->content_en);
        $content_es = old('content_es', $post->content_es);
        $content_it = old('content_it', $post->content_it);
        $photo = old('featured_image', $post->featured_image);
        $photoImg = old('featured_image', $post->featured_image);
    } else {
        $title_en = old('title_en');
        $title_it = old('title_it');
        $title_es = old('title_es');
        $content_en = old('content_en');
        $content_es = old('content_es');
        $content_it = old('content_it');
        $photo = old('featured_image', $post->featured_image);
        $photoImg = old('featured_image', $post->featured_image);
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
            <form method="POST" action="{{ route('admin.post.store') }}" enctype="multipart/form-data">
                @csrf
                @isset($post)
                    <input type="hidden" name="post_id" value="{{ $post->id }}" />
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

                    {{-- featured image --}}
                    <div class="form-group row">
                        <label class="col-sm-3 col-form-label text-right">{{ trans('main.Featured Image') }}</label>
                        <div class="col-sm-9">
                            <div class="image-input image-input-outline" id="photo_input">
                                <div class="image-input-wrapper" style="background-image: url({{ HTTP_POST_PATH.$photoImg }})"></div>

                                <label class="btn btn-xs btn-icon btn-circle btn-white btn-hover-text-primary btn-shadow" data-action="change" data-toggle="tooltip" title="" data-original-title="Change avatar">
                                <i class="fa fa-pen icon-sm text-muted"></i>
                                <input type="file" name="featured_image" accept=".png, .jpg, .jpeg" value="{{ $photo }}"/>
                                <input type="hidden" name="photo_remove"/>
                                </label>

                                <span class="btn btn-xs btn-icon btn-circle btn-white btn-hover-text-primary btn-shadow" data-action="cancel" data-toggle="tooltip" title="Cancel avatar">
                                <i class="ki ki-bold-close icon-xs text-muted"></i>
                                </span>
                            </div>
                        </div>
                    </div>
                    {{--./ featured image --}}

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

                    {{-- category --}}
                    <div class="form-group row" id="js-div-category">
                        <label class="col-sm-3 col-form-label text-right">{{ trans('main.Category') }}</label>
                        <div class="col-lg-9 col-md-9 col-sm-12">
                            <?php
                                $sel_categories = [];
                                foreach ($post->categories as $category) {
                                    $sel_categories[] = $category->category_id;
                                }
                            ?>
                            <div class="row">
                                @foreach ($categories as $category)
                                <div class="col-md-6 mb-3">
                                    <label class="checkbox">
                                        <input type="checkbox" class="form-control js-checkbox-category" value="{{ $category->id }}"
                                            {{ in_array($category->id, $sel_categories) ? 'checked' : '' }}/>
                                        <span></span>&nbsp;{{ $category->name }}
                                    </label>
                                </div>
                                @endforeach
                            </div>
                        </div>
                    </div>
                    {{-- category --}}

                    {{-- SEO title --}}
                    @foreach($supportedLocales as $localeCode => $properties)
                        <?php $slt =  'seo_title_' . $localeCode; ?>
                        <div class="form-group row">
                            <label class="col-sm-3 col-form-label text-right">{{ trans('main.SEO') }} {{ trans('main.Title') }} ({{ $properties['native'] }})</label>
                            <div class="col-lg-9 col-md-9 col-sm-12">
                                <input type="text" class="form-control" name="{{ $slt }}" value="{{ $seo->$slt ?? '' }}" />
                            </div>
                        </div>
                    @endforeach
                    {{--./ SEO title --}}

                    {{-- SEO keyword --}}
                    @foreach($supportedLocales as $localeCode => $properties)
                        <?php $slk =  'keyword_' . $localeCode; ?>
                        <div class="form-group row">
                            <label class="col-sm-3 col-form-label text-right">{{ trans('main.SEO') }} {{ trans('main.Keyword') }} ({{ $properties['native'] }})</label>
                            <div class="col-lg-9 col-md-9 col-sm-12">
                                <input type="text" class="form-control" name="{{ $slk }}" value="{{ $seo->$slk ?? '' }}" />
                            </div>
                        </div>
                    @endforeach
                    {{--./ SEO keyword --}}

                    {{-- SEO description --}}
                    @foreach($supportedLocales as $localeCode => $properties)
                        <?php $sld =  'description_' . $localeCode; ?>
                        <div class="form-group row">
                            <label class="col-sm-3 col-form-label text-right">{{ trans('main.SEO') }} {{ trans('main.Description') }} ({{ $properties['native'] }})</label>
                            <div class="col-lg-9 col-md-9 col-sm-12">
                                <textarea class="form-control" name="{{ $sld }}" rows="3">{{ $seo->$sld ?? '' }}</textarea>
                            </div>
                        </div>
                    @endforeach
                    {{--./ SEO description --}}
                </div>
                
                <div class="card-footer">
                    <button type="submit" onclick="return validate()" class="btn btn-success font-weight-bold mr-2 float-right">@lang('main.Submit')</button>
                    <a href="{{ route('admin.post.index') }}" class="btn btn-light-success font-weight-bold">@lang('main.Back')</a>
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

    function validate() {
        var objList = $("input.js-checkbox-category:checked");
        for (var i = 0; i < objList.length; i++) {
            $("div#js-div-category").append($("<input type='hidden' name='category[]' value=" + objList.eq(i).val() + ">"));
        }
        return true;
    }
</script>
@endsection
