{{-- Extends layout --}}
@extends('user.layout.default')

@section('styles')
    {{ Html::style(userAsset('css/flexslider.css')) }}
    {{ Html::style(userAsset('css/owl.carousel.min.css')) }}
    {{ Html::style(userAsset('css/owl.theme.default.min.css')) }}
@endsection

@section('content-class', 'content-home')
@section('header-transparent', 'header-transparent')

{{-- Content --}}
@section('content')
{{------------ Introduction Slider ------------}}
<section class="main-slider">
    <ul class="slides"> 
        @for ($sliderIdx = 0; $sliderIdx < 4; $sliderIdx++)
            <li class="bg-slider slider-rtl-2 d-flex align-items-center" style="background:url('img/app_image/slider-{{ $sliderIdx + 1 }}.jpg') no-repeat left top; background-size: cover">
                <div class="container slider-content-container">
                    <div class="row align-items-center mt-5">
                        <div class="col-lg-7 col-md-7">
                            <div class="title-heading mt-4">
                                <h1 class="display-4 text-light font-weight-bold mb-3">{{ trans('slider.' . $sliderIdx . '.title') }}</h1>
                                <p class="para-desc text-light para-dark">{{ trans('slider.' . $sliderIdx . '.tagLine') }}</p>
                                <a href="{{ route('user.services.search') }}" class="btn btn-primary" style="padding: 15px 20px; font-size: 18px;">@lang('main.Go to Services')</a>
                            </div>
                        </div><!--end col-->
                    </div><!--end row-->
                </div><!--end container-->
            </li>
        @endfor
    </ul>
</section><!--end section-->
{{------------./ Introduction Slider ------------}}


{{------------ How It Work ------------}}
<section class="section section-home-pro-work">
    <div class="row justify-content-center">
        <div class="col-12 text-center">
            <div class="section-title mb-4 pb-2">
                <h4 class="title mb-4">@lang('main.How the website for professional works')</h4>
            </div>
        </div>
        <!--end col-->
    </div>
    <!--end row-->

    <div class="container">
        <div class="row align-items-center">
            <div class="col-lg-6 col-md-6 text-center">
                <img src="{{ imageAsset('app_home/1.png') }}" alt="">
            </div><!--end col-->

            <div class="col-lg-6 col-md-6 mt-4 mt-sm-0 pt-2 pt-sm-0">
                <div class="section-title ml-lg-5">
                    <h4 class="title mb-4">@lang('main.Home Search for professionals or services')</h4>
                    <p class="text-muted">@lang('main.Home Search for professionals or services description')</p>
                </div>
            </div><!--end col-->
        </div><!--end row-->
    </div><!--end container-->

    <div class="container">
        <div class="row align-items-center">
            <div class="col-lg-7 col-md-6 order-2 order-md-1 mt-4 mt-sm-0 pt-2 pt-sm-0">
                <div class="section-title mr-lg-5">
                    <h4 class="title mb-4">@lang('main.Home Choose the date, order or book')</h4>
                    <p class="text-muted">@lang('main.Home Choose the date, order or book description')</p>
                </div>
            </div><!--end col-->

            <div class="col-lg-5 col-md-6 order-1 order-md-2">
                <img src="{{ imageAsset('app_home/2.png') }}" alt="">
            </div><!--end col-->
        </div><!--end row-->
    </div><!--end container-->

    <div class="container">
        <div class="row align-items-center">
            <div class="col-lg-6 col-md-6 text-center">
                <img src="{{ imageAsset('app_home/3.png') }}" alt="">
            </div><!--end col-->

            <div class="col-lg-6 col-md-6 mt-4 mt-sm-0 pt-2 pt-sm-0">
                <div class="section-title ml-lg-5">
                    <h4 class="title mb-4">@lang('main.Home Decide how and when to pay')</h4>
                    <p class="text-muted">@lang('main.Home Decide how and when to pay description')</p>
                </div>
            </div><!--end col-->
        </div><!--end row-->
    </div><!--end container-->

    <br />
    <div class="row justify-content-center">
        <div class="col-12 text-center mt-4 pt-2">
            <a href="{{ route('user.howitworks') }}" class="btn btn-primary">@lang('main.HowItWorks.home.button') <i class="mdi mdi-chevron-right"></i></a>
        </div>
        <!--end col-->
    </div>
</section>
{{----------./ How It Work ------------}}


{{------------ Categories ------------}}
<section class="section section-category bg-light">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-12 text-center">
                <div class="section-title mb-4 pb-2">
                    <h4 class="title mb-4">@lang('homepage.ServiceSection.Title')</h4><br />
                </div>
            </div>
            <!--end col-->
        </div>
        <!--end row-->

        <div class="row">
            @foreach ($categories as $category)
                @php
                    $idx = $loop->index;
                    $iteration = $loop->iteration;
                    $col = (!($idx % 3) && $idx % 2) || (!($iteration % 3) && $iteration % 2) ? "6" : "3";
                @endphp
                <div class="category-wrapper col-md-{{ $col }} mt-4 pt-2">
                    <div class="card work-container work-modern rounded border-0 overflow-hidden">
                        <div class="card-body p-0 bg-dark">
                            <a href="{{ route('user.services.search', $category->slug) }}">
                                <img src="{{ imageAsset('app_category/' . $category->image) }}" class="img-fluid rounded" alt="category-img">
                                <div class="overlay-work bg-black"></div>
                                <div class="content">{{ $category->name }}</div>
                            </a>
                        </div>
                    </div>
                </div>
                <!--end col-->
            @endforeach
        </div>
        <!--end row-->
        <br />
        <div class="row justify-content-center">
            <div class="col-12 text-center mt-4 pt-2">
                <a href="{{ route('user.services.search') }}" class="btn btn-primary">@lang('main.home.allCategory') <i class="mdi mdi-chevron-right"></i></a>
            </div>
            <!--end col-->
        </div>
        <!--end row-->
    </div>
    <!--end container-->
</section>
{{----------./ Categories ------------}}

{{------------ Posts ------------}}
<section class="section home-post">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-12 text-center">
                <div class="section-title mb-4 pb-2">
                    <h4 class="title mb-4">@lang('main.Latest Posts from our Professionals')</h4>
                </div>
            </div>
            <!--end col-->
        </div>
        <!--end row-->

        <div class="row justify-content-center">
            <div class="col-lg-12 mt-4">
                <div id="latest-post" class="owl-carousel owl-theme">
                    @foreach($posts as $post)
                        <div class="card courses-desc overflow-hidden rounded shadow border-0 m-2">
                            <div class="position-relative d-block overflow-hidden">
                                <img src="{{ HTTP_POST_PATH . $post->featured_image }}" class="img-fluid rounded-top mx-auto" alt="">
                                <div class="overlay-work"></div>
                            </div>

                            <div class="card-body">
                                <h4><a href="{{ route('user.blog.category', $post->category->slug ) }}">{{ $post->category->name }}</a></h4>
                                <h4>
                                    <a href="{{ route('user.blog.detail', $post->slug) }}" class="title text-dark"> {{ str_limit( $post->title, 40) }} </a>
                                </h4>
                                <h6>@lang('main.Author') <a href="{{ route('user.blog.author', $post->user->slug ) }}">{{ $post->user->name }}</a></h6>
                                <hr />
                                <div class="fees d-flex justify-content-between">
                                    <p class="text-secondary post-desc">{{ substr( $post->min_content, 0, 80 ) }}...</p>
                                </div>
                            </div>
                        </div>
                        <!--card-->
                    @endforeach
                </div>
                <!--carousel-->
            </div>
            <!--end col-->
        </div>
        <!--end row-->

        <div class="row justify-content-center">
            <div class="col-12 text-center mt-4 pt-2">
                <a href="{{ route('user.blog.search') }}" class="btn btn-primary">@lang('main.View-all-posts') <i class="mdi mdi-chevron-right"></i></a>
            </div>
            <!--end col-->
        </div>
        <!--end row-->
    </div>
</section>
{{----------./ Posts ------------}}

{{------------ World of professions ------------}}
<section class="section bg-light world-profession">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-12 text-center">
                <div class="section-title mb-4 pb-2">
                    <h4 class="title mb-4">@lang('main.World of Professions')</h4>
                </div>
            </div>
            <!--end col-->
        </div>
        <!--end row-->

        <div class="row justify-content-center">
            <div class="col-lg-12 mt-4">
                <div id="world-profession" class="owl-carousel owl-theme">
                    @foreach($wofs as $wof)
                        <div class="card courses-desc overflow-hidden rounded shadow border-0 m-2">
                            <div class="position-relative d-block overflow-hidden">
                                <img src="{{ '/upload/howitworks/' . $wof->image }}" class="img-fluid rounded-top mx-auto" alt="">
                                <div class="overlay-work"></div>
                            </div>

                            <div class="card-body">
                                <h4>
                                    <a href="{{ route('user.blog.worldprofession.detail', $wof->slug) }}" class="title text-dark">{{ str_limit( $wof->title, 40 ) }}</a>
                                </h4>
                                
                                <hr />
                                <div class="fees d-flex justify-content-between">
                                    <p class="text-secondary post-desc">{{ substr( $wof->min_content, 0, 80 ) }}...</p>
                                </div>
                            </div>
                        </div>
                        <!--card-->
                    @endforeach
                </div>
                <!--carousel-->
            </div>
            <!--end col-->
        </div>
        <!--end row-->

        <div class="row justify-content-center my-md-5 pt-md-3 my-4 pt-2 pb-lg-5 mt-sm-0 pt-sm-0">
            <div class="col-12 text-center mt-4 pt-2">
                <a href="{{ route('user.blog.worldprofession.index') }}" class="btn btn-primary">@lang('main.View-all-W-of-Pro')<i class="mdi mdi-chevron-right"></i></a>
            </div>
            <!--end col-->
        </div>
        <!--end row-->
    </div>
</section>
{{----------./ World of professions ------------}}

{{-- welcome modal --}}
@if ($is_first_login)
<div class="modal fade" id="welcome-modal" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title w-100 text-center">@lang('main.welcome.buyer.title')</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <i aria-hidden="true" class="ki ki-close"></i>
                </button>
            </div>
            <div class="modal-body">
                {!! trans('main.welcome.buyer') !!}
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-primary font-weight-bold float-right" data-dismiss="modal">@lang('main.welcome.buyer.close')</button>
            </div>
        </div>
    </div>
</div>
@endif
@endsection

{{-- Scripts Section --}}
@section('scripts')
    {{ Html::script(userAsset('js/jquery.flexslider-min.js')) }}
    {{ Html::script(userAsset('js/flexslider.init.js')) }}
    {{ Html::script(userAsset('js/owl.carousel.min.js')) }}
    {{ Html::script(userAsset('js/owl.init.js')) }}

    <script>
        $(function(){
            if ($('#welcome-modal')) {
                $('#welcome-modal').modal();
            }
        })
    </script>
    
@endsection
