@php
    $isWorldProfession = isset($is_world_profession);
@endphp
{{-- Extends layout --}}
@extends('user.layout.default')

@section('styles')
    {{ Html::style(userAsset('pages/post/post.css')) }}
@endsection

@section('content')
    <section class="bg-content d-flex align-items-center">
        <div class="container">
            <div class="text-center">
                @if ($isWorldProfession)
                    <h2 class="title">{{ trans('main.World of Professions') }}</h2>
                    <h5>{{ trans('main.world-of-professions page description') }}</h5>
                @else
                    <h2 class="title">{{ trans('main.blog-title') }}</h2>
                    <h5>{{ trans('main.blog page description') }}</h5>
                @endif
            </div>
            <br /><br />

            <div class="row justify-content-center">
                {{-- post content --}}
                <div class="col-md-8 order-sm-first order-last">
                    @foreach($posts as $post)
                        @php
                            $photoPath = $isWorldProfession ? (HTTP_HOWITWORKS_PATH . $post->image): (HTTP_POST_PATH . $post->featured_image);
                        @endphp
                        <div class="post-detail-row mb-16">
                            <div>
                                <img class="blog-img" src="{{ $photoPath }}"/>
                            </div>

                            <div class="d-flex justify-content-between mt-6">
                                <h3>{{ $post->title }}</h3>
                                <a href="javascript:void(0)">
                                    <span class="fas fa-share-alt icon-lg text-dark"
                                        onclick="openShareModal(event)"
                                        data-title="{{ $post->title }}"
                                        data-url="{{ url()->full() }}"
                                        data-photo="{{ $photoPath }}"
                                        data-desc="{{ $post->min_content }}">
                                    </span>
                                </a>
                            </div>

                            @if (!$isWorldProfession)
                            <div class="row mt-6">
                                <div class="col-md-8">
                                    <ul class="post-info">
                                        <li class="mr-8 d-block d-sm-inline-block">
                                            <i class="fa fa-user mr-1" aria-hidden="true"></i>
                                            <a href="{{ route('user.blog.author', $post->user->slug ) }}">{{ $post->user->initial_name }}</a>
                                        </li>

                                        @if($post->category)
                                            <li class="mr-8 d-block d-sm-inline-block">
                                                <i class="fa fa-tag mr-1" aria-hidden="true"></i>
                                                <a href="{{ route('user.blog.category', $post->category->slug ) }}">{{ $post->category->name }}</a>
                                            </li>
                                        @endif

                                        <li class="d-block d-sm-inline-block">
                                            <i class="fa fa-calendar mr-1" aria-hidden="true"></i>
                                            <a href="javascript:;">{{ date("m/Y",strtotime($post->created_at)) }}</a>
                                        </li>
                                    </ul>
                                </div>
                                
                                <div class="col-md-4">
                                    <div class="float-sm-right">
                                        <ul class="post-info">
                                            <li class="mr-4">
                                                <i class="fa fa-eye mr-1" aria-hidden="true"></i>
                                                <a href="javascript:void(0)">{{ $post->unique_views }}</a>
                                            </li>
                                            <li>
                                                <i class="fa fa-comment mr-1" aria-hidden="true"></i>
                                                <a href="{{ route('user.blog.detail', $post->slug) }}">{{ $post->comment_count }}</a>
                                            </li>
                                        </ul>
                                    </div>
                                </div>
                            </div>
                            @endif

                            <div class="text-content">
                                @if(isset($is_detail))
                                    <p>{!! $post->content !!}</p>
                                @else
                                    <p>{!! $post->min_content !!}</p>
                                @endif
                            </div>

                            @empty($is_detail)
                                <div class="text-right">
                                    <a class="btn btn-primary" href="{{ $isWorldProfession ? route('user.blog.worldprofession.detail', $post->slug) : route('user.blog.detail', $post->slug) }}">
                                        {{ trans('main.Read More') }} <i class="fa fa-chevron-right"></i>
                                    </a>
                                </div>
                            @endisset
                        </div>
                    @endforeach

                    @empty($is_detail)
                        <br />
                        <div class="float-right">{{ $posts->links() }}</div>
                        <div class="clearfix"></div>
                    @endisset
                </div>
                {{-- ./post content --}}

                {{-- search box --}}
                <div class="col-md-3 order-sm-last order-first mb-10">
                    @include('user.pages.post.blog-search-bar')
                </div>
                {{-- ./search box --}}
            </div>
        </div>
    </section>

    @include('user.components.share-modal')
@endsection

@section('scripts')
    @include('user.include-js.share-js')
@endsection
