@php
    $supportedLocales = LaravelLocalization::getSupportedLocales() ?: [];
    if (isset($post)) {
        $categoryId = old('category_id', $post->category_id);
        $title_en = old('title_en', $post->title_en);
        $title_it = old('title_it', $post->title_it);
        $title_es = old('title_es', $post->title_es);
        $content_en = old('content_en', $post->content_en);
        $content_it = old('content_it', $post->content_it);
        $content_es = old('content_es', $post->content_es);
        $photo = old('featured_image', $post->featured_image);
        $photoImg = old('featured_image', $post->featured_image);
    } else {
        $categoryId = old('category_id');
        $title_en = old('title_en');
        $title_it = old('title_it');
        $title_es = old('title_es');
        $content_en = old('content_en');
        $content_it = old('content_it');
        $content_es = old('content_es');
        $photo = old('featured_image');
        $photoImg = old('featured_image', DEFAULT_PHOTO);
    }

    $defaultLang = auth()->user()->default_language ?: app()->getLocale();
    $supportLocales = \LaravelLocalization::getSupportedLocales();
    // Debugbar::info($errors);
@endphp

{{-- Extends layout --}}
@extends('user.layout.default')

{{-- Content --}}
@section('content')
    <section class="bg-content d-flex align-items-center">
        <div class="container">
            <div class="row justify-content-center px-8 px-lg-10">
                <div class="col-xl-12 col-xxl-8">
                    <form class="form" method="POST" action="{{ route('user.post.store') }}" enctype='multipart/form-data'>
                        @csrf

                        @isset($post)
                            <input type="hidden" name="post_id" value="{{ $post->id ?? null }}" />
                        @endisset

                        <h3 class="mb-10 font-weight-bold text-dark">@lang('main.Post Article')</h3>

                        @include('user.components.validation-top-error')

                        <!-- post title -->
                        <div class="form-group">
                            <label>@lang('main.Post Title') <span class="text-danger">*</span></label>
                            <ul class="nav nav-tabs" id="title-tab-links" role="tablist">
                                {{-- default title tab link --}}
                                <li class="nav-item">
                                    <a class="nav-link active" id="{{ $defaultLang }}-title-tab" data-toggle="tab" href="#title-{{ $defaultLang }}" role="tab" aria-controls="title-{{ $defaultLang }}" aria-selected="true">{{ ucfirst($supportLocales[$defaultLang]['native']) }}</a>
                                </li>
                                {{--./ default title tab link --}}

                                {{-- other title tab link --}}
                                @foreach($supportLocales as $localeCode => $locale)
                                    @if ($localeCode != $defaultLang)
                                        <li class="nav-item">
                                            <a class="nav-link" id="{{ $localeCode }}-title-tab" data-toggle="tab" href="#title-{{ $localeCode }}" role="tab" aria-controls="title-{{ $localeCode }}" aria-selected="true">{{ ucfirst($locale['native']) }}</a>
                                        </li>
                                    @endif
                                @endforeach
                                {{--./ other title tab link --}}
                            </ul>

                            <div class="tab-content" id="title-tab-content">
                                {{-- default title input --}}
                                <div class="tab-pane fade show active" id="title-{{ $defaultLang }}" role="tabpanel" aria-labelledby="{{ $defaultLang }}-title-tab">
                                    <input type="text" name="title_{{ $defaultLang }}" class="form-control form-control-lg profile-desc @error('title_' . $defaultLang) is-invalid @enderror"
                                        placeholder="@lang('main.Post Title')" value="{{ ${'title_' . $defaultLang} }}" />

                                    @include('user.components.validation-error', ['field' => 'title_' . $defaultLang])
                                </div>
                                {{-- default title input --}}

                                {{-- other title input --}}
                                @foreach($supportLocales as $localeCode => $locale)
                                    @if ($localeCode != $defaultLang)
                                        <div class="tab-pane fade" id="title-{{ $localeCode }}" role="tabpanel" aria-labelledby="{{ $localeCode }}-title-tab">
                                            <input type="text" name="title_{{ $localeCode }}" class="form-control form-control-lg profile-desc @error('title_' . $localeCode) is-invalid @enderror"
                                                placeholder="@lang('main.Post Title')" value="{{ ${'title_' . $localeCode} }}" />
                                            
                                            @include('user.components.validation-error', ['field' => 'title_' . $localeCode])
                                        </div>
                                    @endif
                                @endforeach
                                {{--./ other title input --}}
                            </div>
                        </div>
                        <!--./ post title -->

                        <!-- post category -->
                        <div class="form-group">
                            <label>@lang('main.Category') <span class="text-danger">*</span></label>
                            <select name="category_id" class="form-control form-control-lg @error('category_id') is-invalid @enderror">
                                <option value="">{{ trans('main.Select') }} {{ trans('main.Category') }}</option>
                                @foreach($categories as $category)
                                    <option value="{{ $category->id }}" {{$category->id == $categoryId ? 'selected':''}}>{{ $category->name }}</option>
                                @endforeach
                            </select>

                            @include('user.components.validation-error', ['field' => 'category_id'])
                        </div>
                        <!--./ post category -->

                        {{-- content --}}
                        <div class="form-group">
                            <label>@lang('main.Description') <span class="text-danger">*</span></label>
                            <ul class="nav nav-tabs" id="content-tab-links" role="tablist">
                                {{-- default content tab link --}}
                                <li class="nav-item">
                                    <a class="nav-link active" id="{{ $defaultLang }}-content-tab" data-toggle="tab" href="#content-{{ $defaultLang }}" role="tab" aria-controls="content-{{ $defaultLang }}" aria-selected="true">{{ ucfirst($supportLocales[$defaultLang]['native']) }}</a>
                                </li>
                                {{--./ default content tab link --}}

                                {{-- other content tab link --}}
                                @foreach($supportLocales as $localeCode => $locale)
                                    @if ($localeCode != $defaultLang)
                                        <li class="nav-item">
                                            <a class="nav-link" id="{{ $localeCode }}-content-tab" data-toggle="tab" href="#content-{{ $localeCode }}" role="tab" aria-controls="content-{{ $localeCode }}" aria-selected="true">{{ ucfirst($locale['native']) }}</a>
                                        </li>
                                    @endif
                                @endforeach
                                {{--./ other content tab link --}}
                            </ul>

                            <div class="tab-content" id="content-tab-content">
                                {{-- default title input --}}
                                <div class="tab-pane fade show active" id="content-{{ $defaultLang }}" role="tabpanel" aria-labelledby="{{ $defaultLang }}-content-tab">
                                    <textarea name="content_{{ $defaultLang }}" class="content-ck-editor" rows="10"
                                        placeholder="{{ trans('main.placeholder post', [], $defaultLang) }}">
                                        {!! ${'content_' . $defaultLang} !!}
                                    </textarea>
                                    <div class="counter-status text-right text-muted"><span class="current-count"></span> / <span class="total-count"></span></div>

                                    @include('user.components.validation-error', ['field' => 'content_' . $defaultLang])
                                </div>
                                {{-- default title input --}}

                                {{-- other title input --}}
                                @foreach($supportLocales as $localeCode => $locale)
                                    @if ($localeCode != $defaultLang)
                                        <div class="tab-pane fade" id="content-{{ $localeCode }}" role="tabpanel" aria-labelledby="{{ $localeCode }}-content-tab">
                                            <textarea name="content_{{ $localeCode }}" class="content-ck-editor" rows="10"
                                                placeholder="{{ trans('main.placeholder post', [], $localeCode) }}">
                                                {!! ${'content_' . $localeCode} !!}
                                            </textarea>
                                            
                                            @include('user.components.validation-error', ['field' => 'content_' . $localeCode])
                                        </div>
                                    @endif
                                @endforeach
                                {{--./ other title input --}}
                            </div>
                        </div>
                        {{--./ content --}}

                        {{-- photo --}}
                        <div class="form-group">
                            <label>@lang('main.Featured Image')</label>
                            <div class="d-block d-sm-inline-block mb-4">@lang('main.Featured Image description')</div>
                            
                            <div class="image-input image-input-outline" id="photo_input">
                                <div class="image-input-wrapper" style="background-image: url(/upload/post/{{ $photoImg }})"></div>

                                <label class="btn btn-xs btn-icon btn-circle btn-white btn-hover-text-primary btn-shadow" data-action="change" data-toggle="tooltip" title="" data-original-title="@lang('main.Change image')">
                                <i class="fa fa-pen icon-sm text-muted"></i>
                                <input type="file" name="featured_image" accept=".png, .jpg, .jpeg" value="{{ $photo }}"/>
                                <input type="hidden" name="featured_image_remove"/>
                                </label>

                                <span class="btn btn-xs btn-icon btn-circle btn-white btn-hover-text-primary btn-shadow" data-action="cancel" data-toggle="tooltip" title="@lang('main.Cancel image')">
                                <i class="ki ki-bold-close icon-xs text-muted"></i>
                                </span>
                            </div>
                        </div>
                        {{--./ photo --}}

                        <div class="mt-5">
                            <div class="float-right">
                                <button type="submit" class="btn btn-primary font-weight-bold px-9 py-2">@lang('main.Save and publish')</button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </section>
@endsection

{{-- Scripts Section --}}
@section('scripts')
    <script src="{{ adminAsset('plugins/custom/ckeditor/ckeditor-classic.bundle.js?v=7.0.5') }}" type="text/javascript"></script>
    <script src="{{ adminAsset('custom/ck-editor.js') }}" type="text/javascript"></script>
    <script>
        new KTImageInput('photo_input');
    </script>
@endsection