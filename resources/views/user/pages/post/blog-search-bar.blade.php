<div class="card">
    <div class="card-body sidebar">
        <div class="widget">
            <div class="widget-search">
                <form role="search" method="get" action="{{ route('user.blog.search') }}" id="searchform" class="searchform">
                    <input type="text" class="border rounded" name="keyword" id="title"
                        placeholder="{{ $isWorldProfession ? trans('main.Search on WofP') : trans('main.blog.search.keyword')}}"
                        value="{{ request()->get('keyword') }}" />
                    <input type="submit" id="searchsubmit" value="Search">
                </form>
            </div>
        </div>
    </div>
</div>
<br />

@if (!$isWorldProfession)
<div class="card faq-content p-4">
    <div class="accordion" id="accordionBlogCategory">
        <div class="card border-0 rounded mb-2">
            <a data-toggle="collapse" href="#collapseBlogCategory" class="faq position-relative collapsed"
                aria-expanded="false" aria-controls="collapseone">
                <div class="card-header border-0 bg-white p-3 pr-5" id="headingfifone">
                    <h5 class="title mb-0">@lang('main.blog-categories')</h5>
                </div>
            </a>

            <div style="padding: 0 10px;">
                <div id="collapseBlogCategory" class="collapse" aria-labelledby="headingfifone"
                    data-parent="#accordionBlogCategory" style="padding: 10px 0 0; border-top: 1px solid #efefef;">
                    @foreach($post_categories as $category)
                        @php
                            $postCount = $category->posts()->count();
                        @endphp
                        @if ($postCount > 0)
                            <div class="mb-2">
                                <a href="{{ route('user.blog.category', $category->slug ) }}">
                                    {{ $category->name }} ({{ $postCount }})
                                </a>
                            </div>
                        @endif
                    @endforeach
                </div>
            </div>
        </div>
    </div>
</div>
<br />
@endif

<div class="card faq-content p-4">
    <div class="accordion" id="accordionWorldProfession">
        <div class="card border-0 rounded mb-2">
            <a data-toggle="collapse" href="#collapseWorldProfession" class="faq position-relative collapsed"
                aria-expanded="false" aria-controls="collapseone">
                <div class="card-header border-0 bg-white p-3 pr-5" id="headingfifone">
                    <h5 class="title mb-0">@lang('main.World of Professions')</h5>
                </div>
            </a>

            <div style="padding: 0 10px;">
                <div id="collapseWorldProfession" class="collapse" aria-labelledby="headingfifone"
                    data-parent="#accordionWorldProfession" style="padding: 10px 0 0; border-top: 1px solid #efefef;">
                    @if ($isWorldProfession)
                        @foreach($world_of_professions as $post)
                            <div class="mb-2">
                                <a href="{{ route('user.blog.worldprofession.detail', $post->slug) }}">
                                    {{ $post->title }}
                                </a>
                            </div>
                        @endforeach
                    @else
                        <a href="{{ route('user.blog.worldprofession.index') }}">
                            @lang('main.All posts about Professions') ({{ $cntWop }})
                        </a>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>