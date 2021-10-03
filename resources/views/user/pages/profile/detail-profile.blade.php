@php
    use App\Models\EnrollType;
    $ratePrice = number_format(floatval($user->hourly_rate), 2);
@endphp

{{-- Extends layout --}}
@extends('user.layout.default')

@section('meta-seo')
    <meta property="og:type" content="website" />
    <meta name="twitter:card" content="summary" />
    <meta name="twitter:site" content="@weredyofficial" />
    <meta property="og:url" content="{{URL::current()}}" />
    <meta property="og:title" content="{{ $user->initial_name }}" />
    <meta property="og:description" content="{{$user->description}}" />
    <meta property="og:image" content="{{ HTTP_USER_PATH . $user->detail->photo }}" />
    <meta property="fb:app_id" content="{{env('FACEBOOK_APP_ID')}}" />
@endsection

@section('styles')
    {{ Html::style(userAsset('pages/profile/detail.css')) }}
    {{ Html::style(userAsset('pages/post/post.css')) }}
@endsection

@section('content')
<section class="bg-content d-flex align-items-center">
    <div class="container">
        {{-- Top Detail --}}
        <div class="card detail-info-wrapper">
            <div class="card-body">
                <div class="row detail-row-container">
                    {{-- detail profile left --}}
                    <div class="col-md-auto mr-auto profile-detail-left">
                        <div class="d-flex">
                            <div class="mr-7 mt-lg-0 mt-3">
                                <div class="service-img-wrapper symbol symbol-120 symbol-lg-120">
                                    <img id="img-user-photo" class="border-round" src="{{ HTTP_USER_PATH . $user->detail->photo }}" alt="image">
                                </div>
                            </div>

                            {{-- detail content --}}
                            <div>
                                <p class="mb-4">
                                    <a href="{{ URL::route('user.professionals.detail', $user->slug) }}"
                                        class="text-dark-75 text-hover-primary font-size-h3 font-weight-bolder mr-3">
                                        {{ $user->name . " " . strtoupper(substr($user->last_name, 0, 1))."." }}</a>
                                </p>
                                
                                <div class="mb-2 professional-category-container">
                                    @foreach($user->profsByUser as $profCategory)
                                        <a href="{{ URL::route('user.professionals.search', ['category_name' => $profCategory->category->slug,
                                                'sub_category_name' => $profCategory->profession->slug]) }}">
                                            {{ $profCategory->profession->name }} </a>
                                        {{ $loop->last ? '' : ' - ' }}
                                    @endforeach
                                </div>
                                <div class="mb-2">
                                    <span style="width: 137px;">
                                        @if($user->is_online == "1")
                                            <span class="label label-xl label-success label-dot mr-2"></span>
                                            <span class="font-weight-bold">@lang('main.ONLINE')</span>
                                        @else
                                            <span class="label label-xl label-failed label-dot mr-2"></span>
                                            <span class="font-weight-bold">@lang('main.OFFLINE')</span>
                                        @endif
                                    </span>
                                </div>

                                @if($isOffsite)
                                    <div class="mb-2">
                                        <span style="width: 137px;">
                                            <span class="label label-xl label-success label-dot mr-2"></span>
                                            <span class="font-weight-bold">@lang('main.Available for Offsite Service')</span>
                                        </span>
                                    </div>
                                @endif

                                {{-- rating start --}}
                                <div class="mb-2">
                                    @include('user.components.star-rate', ['score' => $user->review_score]) ({{ $user->getReviewCount() }})
                                </div>
                                {{--./ rating start  --}}
                            </div>
                            {{--./ detail content  --}}

                            <div class="share-icon-wrapper ml-auto">
                                @auth
                                    @if($user->getFavouriteId() == null)
                                        <a href="#" id="fav_modal_btn_add" onclick="openProfFavModal(event, '{{ $user->id }}', '{{ $user->initial_name }}')">
                                            <span class="far fa-heart icon-lg text-dark"></span>
                                        </a>
                                    @else
                                        <a href="#" id="fav_modal_btn_add" onclick="deleteFavouriteModal({{ $user->getFavouriteId() }}, '{{ \App\Models\Favourite::TYPE_PROFESSIONAL }}')">
                                            <span class="fas fa-heart icon-lg text-warning"></span>
                                        </a>
                                    @endif
                                @endauth

                                <span class="ml-2">
                                    <a href="javascript:void(0)">
                                        <span class="fas fa-share-alt icon-lg text-dark"
                                            onclick="openShareModal(event)"
                                            data-title="{{ $user->initial_name }}"
                                            data-url="{{ url()->full() }}"
                                            data-photo="{{ '/upload/user/' . $user->photo }}"
                                            data-desc="{{ $user->description }}">
                                        </span>
                                    </a>
                                </span>
                            </div>
                        </div>

                        @if ($user->detail->enrollType->id != EnrollType::NOT_ENROLLED)
                            <div class="mt-5 font-size-h5">
                                <span class="font-weight-boldest">@lang('main.Enrolled at')</span> 
                                    {{ $user->detail->enrollType->name }}, {{ $user->detail->association->name }}, {{ $user->detail->city }}<!--
                                -->@if($user->detail->reg_number)
<!--                                -->, <span class="font-weight-boldest">No.</span> {{ $user->detail->reg_number }}
                                @endif
                            </div>
                        @endif
                        <div class="show-read-more mt-5 text-dark-50" style="max-width: 800px;">{{ $user->detail->description }}</div>
                    </div>
                    {{-- ./detail profile left --}}


                    {{-- detail profile right --}}
                    <div class="col-md-auto profile-detail-right mt-4 mt-sm-0">
                        {{-- detail content --}}
                        <div class="detail-right-wrapper">
                            <p class="detail-member-since">
                                <strong>@lang('main.Member Since')</strong> - 
                                @lang('main.' . $user->created_at->formatLocalized('%B')) {{ $user->created_at->formatLocalized('%Y') }}
                            </p>

                            @if($user->detail->country_id)
                                <p>
                                    <strong>@lang('main.Country')</strong> - 
                                    {{ $user->detail->country->short_name}}
                                </p>
                            @endif

                            @if($user->detail->languages)
                                <p><strong>@lang('main.Speaks')</strong> - {{$user->getLang()}}</p>
                            @endif

                            <h3>&euro;{{ $ratePrice > 0 ? $ratePrice : '' }} / @lang('main.per-hrs') {{ $ratePrice == 0 ? 'N.D.' : '' }}</h3>

                            <button type="button" class="btn btn-primary btn-responsive mt-2">@lang('main.CONTACT NOW')</button>
                        </div>
                        {{--./ detail content --}}
                    </div>
                    {{--./ detail profile right --}}
                </div>
            </div>
        </div>
        {{--./ Top Detail --}}
        
        <br />

        <div>
            {{-- Card Start --}}
            <div class="card">
                <div class="card-body">
                    {{-- Nav tabs --}}
                    <ul class="nav nav-tabs" id="available-service-tabs" role="tablist">
                        <li class="nav-item">
                            <a class="nav-link active" data-toggle="tab" href="#tab-available-service">
                                <span class="nav-text">@lang('main.Services')</span>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" id="posts-published-tab" data-toggle="tab" href="#tab-posts-published">
                                <span class="nav-text">@lang('main.Posts')</span>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" id="review-tab" data-toggle="tab" href="#tab-detail-reviews">
                                <span class="nav-text">@lang('main.Recent Reviews')</span>
                            </a>
                        </li>
                    </ul>
                    {{--./ Nav tabs --}}

                    <div class="tab-content mt-5">
                        <div class="tab-pane fade show active" id="tab-available-service" role="tabpanel" aria-labelledby="available-service-tab">
                            <div class="available-services p-4">
                                @foreach ($services as $service)
                                    @include('user.pages.service.detail-service-row')
                                @endforeach

                                <div class="float-right mt-4">
                                    {{ $services->links() }}
                                </div>
                            </div>
                        </div>

                        <div class="tab-pane fade" id="tab-posts-published" role="tabpanel" aria-labelledby="tab-posts-published">
                            <div class="posts-published p-4">
                                @foreach ($posts as $post)
                                    @include('user.pages.post.detail-post-row')
                                @endforeach
                            </div>
                        </div>

                        <div class="tab-pane fade" id="tab-detail-reviews" role="tabpanel" aria-labelledby="tab-detail-reviews">
                            <div>
                                <div class="d-flex justify-content-between flex-column flex-sm-row p-4">
                                    <div class="order-1 order-sm-0">
                                        @php
                                            $userReviewCount = $user->getReviewCount();
                                            $userReviewScore = $user->review_score;
                                        @endphp
                                        <span class=" mr-10">{{ $userReviewCount }} {{ trans('main.Reviews') }}</span>
                                        @include('user.components.star-rate', ['score' => $userReviewScore])
                                    </div>

                                    <div class="order-0 order-sm-1">
                                        <select class="form-control" name="price_range" id="price_range" onchange="setTextField(this)">
                                            @foreach($sort_array as $sortField => $sortLabel)
                                                <option {{ $sortField == $sort_by ? 'selected' : '' }} value="{{ $sortField }}">{{ $sortLabel }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>

                                @foreach($books as $book)
                                    @include('user.pages.profile.detail-review-row')
                                @endforeach
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            {{--./ Card Start --}}
        </div>
    </div>
</section>

@include('user.components.favourite-modal')
@include('user.components.share-modal')
@endsection

@section('scripts')
    @include('user.include-js.favourite-js')
    @include('user.include-js.share-js')

    {{ Html::script(userAsset('libraries/readmore.js')) }}
    @include('user.include-js.readmore-js')

    <script>
        function setTextField(obj) {
            const selectedOption = obj.options[obj.selectedIndex].value;
            const end_url =  '{{ route('user.professionals.detail', $user->slug) }}' + `?sort_by=${selectedOption}` + '#tab-detail-reviews';
            document.location.replace(end_url);	
        }
    </script>
@endsection
