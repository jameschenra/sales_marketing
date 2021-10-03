@php
    use App\Models\Service;    
@endphp
<div class="available_services p-4">
    @foreach ($posts as $post)
        <div class="detail-service-row">
            <div class="row">
                {{-- row left part --}}
                <div class="col-md-9">
                    <div class="detail-service-content">
                        {{-- first column --}}
                        <div class="d-flex mb-3">
                            <div class="service-img-wrapper mr-3">
                                <img class="border-round" src="{{ HTTP_SERVICE_PATH . $service->photo }}" alt="service-logo" />
                            </div>

                            <div>
                                <h3>{{ $service->name }}</h3>
                                <div class="mb-2">
                                    @php
                                        $subCategory = $service->subCategory;
                                    @endphp
                                    @if($subCategory)
                                        <a href="{{ URL::route('user.services.search', ['category_name' =>$subCategory->category->slug]) }}">{{ $subCategory->category->name }}</a> / 
                                        <a href="{{ URL::route('user.services.search', ['category_name' => $subCategory->category->slug, 'sub_category_name' => $subCategory->slug]) }}">
                                            {{ $subCategory->name }}
                                        </a>
                                    @endif
                                </div>

                                @if($service->provide_online_type == Service::PROVIDE_OFFLINE_TYPE)
                                    <div class="mb-2">
                                        <span class="far fa-clock"></span>                                    
                                        <span class="text-primary">{{ $service->prettyDuration }}</span>

                                        @if($service->hasConsecutively())
                                            @lang('main.Possibility to book for more time')
                                        @endif
                                    </div>
                                @endif

                                @if ($service->provide_online_type == Service::PROVIDE_ONLINE_TYPE)
                                    <div class="mb-2">
                                        <span class="fas fa-hand-holding-water"></span>
                                        @lang('main.Delivery in') {{ trans_choice('main.times.day', $service->online_delivery_time, ['d' => $service->online_delivery_time]) }}
                                        &nbsp; <span class="fa fa-file-import"></span>
                                        {{ trans_choice('main.revision', $service->online_revision, ['r' => $service->online_revision]) }} 
                                        {{ trans_choice('main.revision_include', $service->online_revision) }} 
                                    </div>
                                @endif

                                @if($service->provide_online_type == Service::PROVIDE_OFFLINE_TYPE)
                                    <div class="online-status-container">
                                        <span class="label label-xl label-success label-dot mr-2"></span>
                                        <span class="font-weight-bold">@lang('main.Available offsite Services')</span>
                                    </div>
                                @endif
                            </div>
                        </div>
                        {{-- ./first column --}}

                        {{-- second column --}}
                        <div>
                            <div class="d-flex">
                                <div class="mr-2">
                                    @if($user->detail->photo)
                                        <img class="user-logo-mini border-round" src="{{ HTTP_USER_PATH . $user->detail->photo }}" alt="user photo">
                                    @else
                                        <img class="user-logo-mini border-round" src="{{ HTTP_USER_PATH . DEFAULT_PHOTO }}" alt="user photo">
                                    @endif
                                </div>
                                <div>
                                    <div class="font-size-h6 line-height-1">@lang('main.Provided-by')</div>
                                    <div class="font-size-h5 font-weight-boldest">
                                        {{ $user->initial_name }}&nbsp;
                                        @include('user.components.star-rate', ['score' => $service->review_score])
                                        <span class="font-size-h6 font-weight-light m-l-10">({{ $service->getReviewCount() }})</span>
                                    </div>
                                    <div class="">
                                        <p class="mobile font-size-normal text-dark-50">{{ substr($service->description, 0, 200) }}...</p>
                                    </div>
                                </div>
                            </div>
                        </div>
                        {{-- ./second column --}}
                    </div>
                    
                </div>
                {{-- ./row left part --}}

                {{-- row right part --}}
                <div class="col-md-3 d-flex flex-column justify-content-between">
                    <div>
                        <div class="service-price-wrapper">
                            @if($service->price > 0)
                                €{{ $service->price }}.00
                            @else
                                @lang('main.Free Service')
                            @endif
                        </div>

                        <div class="service-price-wrapper">
                            @if($service->price > 0)
                                €{{ $service->price }}.00
                            @else
                                @lang('main.Free Service')
                            @endif
                        </div>

                        <div class="">
                            <div>
                                @lang('main.available in'):<br />
                                @foreach($service->offices as $office_key => $office)
                                    @if($office->office)
                                        <strong>{{ $office->office->city->name ?? '' }}{{ (($office_key + 1) != count($service->offices) ? ',' : '')}}</strong>
                                    @endif
                                @endforeach
                            </div>
                        </div>
                    </div>

                    <button class="btn btn-primary" type="button" onclick="window.open('#','_self')" value="button"> {{ trans('main.View-Service') }}</button>
                </div>
                {{-- ./row right part --}}
            </div>
        </div>
    @endforeach
</div>