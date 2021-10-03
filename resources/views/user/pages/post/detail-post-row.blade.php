<div class="row mb-10 dtail-post-row">
    <div class="col-md-3">
        <div class="detail-post-img">
            <a href="{{ route('user.blog.detail', [$post->slug]) }}">
                @if(!$post->featured_image)
                    <img src="{{ asset('img/default.png') }}" style="max-height:220px;" class="img-responsive thumbnail">
                @else
                    <img class="img-fluid" src="{{ HTTP_POST_PATH . $post->featured_image }}" style="max-height:220px;" alt="Post Image"/>
                @endif
            </a>
        </div>
    </div>
    <div class="col-md-9 detail-post-content-container">
        <h4>{{ $post->title }}</h4>
        <hr />
        <div class="row">
            <div class="col-md-8">
                <ul class="post-info">
                    <li class="mr-5">
                        <i class="fa fa-tag" aria-hidden="true"></i>
                        {{ $post->category->name }}
                    </li>
                    <li>
                        <i class="fa fa-calendar" aria-hidden="true"></i>
                        <a href="javascript:;">{{date("m",strtotime($post->created_at))}} / {{date("Y",strtotime($post->created_at))}}</a>
                    </li>
                </ul>
            </div>

            <div class="col-md-4">
                <div class="float-right">
                    <ul class="post-info">
                        <li class="mr-2">
                            <i class="fa fa-eye" aria-hidden="true"></i>
                            <a href="#">{{ $post->unique_views }}</a>
                        </li>
                        <li>
                            <i class="fa fa-comment" aria-hidden="true"></i>
                            <a href="{{ route('user.blog.detail', $post->slug) }}">{{ $post->comment_count }}</a>
                        </li>
                    </ul>
                </div>
            </div>
        </div>
        {{-- ./row --}}

        <div>
            <p class="text-muted"> {{ substr(strip_tags($post->content), 0, 150) }}... </p>
        </div>

        <div>
            <a href="{{ route('user.blog.detail', $post->slug) }}" class="btn btn-primary float-right">@lang('main.Read More') <i class="fa fa-chevron-right"></i></a>
        </div>
    </div>
</div>