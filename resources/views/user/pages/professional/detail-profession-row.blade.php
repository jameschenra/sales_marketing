@php 
    use App\Models\EnrollType;
    use App\Models\ServiceOffice;
@endphp

<div class="detail-profession-row">
    <div class="row">
        {{-- row left part --}}
        <div class="col-md-9">
            <div class="detail-row-container">
                {{-- first column --}}
                <div class="d-flex flex-column flex-sm-row mb-3 text-center text-sm-left">
                    <div class="service-img-wrapper mr-3">
                        <img class="border-round" src="{{ HTTP_USER_PATH . $user->detail->photo }}" alt="user-logo" />
                    </div>

                    <div>
                        <h3><a href="{{ route('user.professionals.detail', $user->slug) }}">{{ $user->initial_name }}</a></h3>
                        <div class="mb-2 professional-category-container">
                            @foreach($user->profsByUser as $profCats)
                                <a href="{{ URL::route('user.professionals.search', 
                                    [
                                        'category_name' => $profCats->category->slug,
                                        'sub_category_name' => $profCats->profession->slug
                                    ]) }}" class="color-default">{{ $profCats->profession->name }}</a>
                                {{ !$loop->last ? '-' : '' }}
                            @endforeach
                        </div>

                        <div class="font-size-h5 font-weight-boldest">
                            @include('user.components.star-rate', ['score' => $user->review_score])

                            <span class="font-size-h6 font-weight-light m-l-10">({{ $user->getReviewCount() }})</span>
                        </div>

                        @auth
                            <div class="online-status-container">
                                <span class="label label-xl {{ $user->is_online ? 'label-success' : '' }} label-dot mr-2"></span>
                                {{ $user->is_online ? 'Online' : 'Offline' }}
                            </div>
                        @endauth

                        @if(ServiceOffice::isOffsiteByUserId($user->id))
                            <div class="online-status-container">
                                <span class="label label-xl label-success label-dot mr-2"></span>
                                @lang('main.Available for Offsite Service')
                            </div>
                        @endif
                    </div>

                    {{-- share icon --}}
                     @auth
                        <div class="share-icon-wrapper mr-4 ml-auto">
                            @if ($user->getFavouriteId())
                                <a href="javascript:void(0)" id="fav_modal_btn_add" onclick="deleteFavouriteModal({{ $user->getFavouriteId() }}, '{{ \App\Models\Favourite::TYPE_PROFESSIONAL }}')">
                                    <span class="fas fa-heart icon-lg text-warning"></span>
                                </a>
                            @else
                                <a href="javascript:void(0)" id="fav_modal_btn_add" onclick="openProfFavModal(event, '{{ $user->id }}', '{{ $user->initial_name }}')">
                                    <span class="far fa-heart icon-lg text-dark"></span>
                                </a>
                            @endif

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
                    @endauth
                    {{-- ./ share icon --}}
                </div>
                {{-- ./first column --}}

                {{-- second column --}}
                @if ($user->detail->enrollType->id != EnrollType::NOT_ENROLLED)
                <div class="text-center text-sm-left mb-5">
                    <strong>{{ trans('main.Enrolled at') }}</strong>
                    {{ $user->detail->enrollType->name }}, {{ $user->detail->association->name }}, {{ $user->detail->city }}<!--
                    
                    -->@isset($user->detail->reg_number)<!--
                    --><div class="d-sm-inline-block"><!--
                        -->, <strong>@lang('main.enroll no')</strong> {{ $user->detail->reg_number }}
                        </div>
                    @endisset
                </div>
                @endif
                {{-- ./second column --}}

                <div class="d-none d-sm-block">
                    <div class="show-read-more" style="word-break: break-all;">{{ $user->detail->description }}</div>
                </div>
            </div>
            
        </div>
        {{-- ./row left part --}}

        {{-- row right part --}}
        <div class="col-md-3 d-flex flex-column text-center text-sm-left">
            <div class="mb-2">
                <strong>{{ trans('main.Member Since') }}</strong>
                {{ trans('main.'.$user->created_at->formatLocalized('%B')) }} {{ $user->created_at->formatLocalized('%Y') }}
            </div>

            <div class="mb-4">
                <strong>{{ trans('main.Country') }}</strong>
                {{ $user->detail->country->short_name }}
            </div>

            @if($user->detail->languages)
                <div class="mb-4">
                    <strong>{{ trans('main.Speaks') }}</strong> {{$user->getLang()}}
                </div>
            @endif

            <div class="service-price-wrapper text-center mb-4">
                <?php $price = number_format(floatval($user->hourly_rate), 2); ?>
                &euro;{{ $price > 0 ? $price : '' }}/{{ trans('main.per-hrs') }} {{ $price == 0 ? 'N.D.' : '' }}
            </div>

            <div class="text-center mt-auto">
                <a href="{{ route('user.professionals.detail', $user->slug) }}"><button class="btn btn-primary btn-responsive btn-view-detail" type="button" value="button">
                    @lang('main.View-Profile')</button></a>
            </div>
            
        </div>
        {{-- ./row right part --}}
    </div>
</div>