{{-- service title --}}
<div class="row">
    <div class="col-10">
        <h1> {{$service->name}}</h1>
    </div>
    <div class="col-2 text-right">
        @auth
            @if ($service->getFavouriteId() == 0)
                <a href="#" id="fav_modal_btn_add" onclick="openServiceFavModal(this, {{ $service->id }}, '{{ $service->name }}')">
                    <span class="far fa-heart icon-xl text-dark"></span>
                </a>
            @else
                <a href="#" id="fav_modal_btn_remove" onclick="return deleteServFavModal({{ $service->getFavouriteId() }})">
                    <span class="fas fa-heart icon-xl text-warning"></span>
                </a>
            @endif
        @endauth

        <span class="share-icon ml-2">
            <a href="javascript:void(0)">
                <span class="fas fa-share-alt icon-lg text-dark"
                    onclick="openShareModal(event)"
                    data-title="{{ $service->name }}"
                    data-url="{{ url()->full() }}"
                    data-photo="{{ '/upload/service/' . $service->photo }}"
                    data-desc="{{ $service->description }}">
                </span>
            </a>
        </span>
    </div>
</div>
{{-- ./service title --}}

<div>
    <a href="{{ route('user.services.search', ['category_name' => $service->category->slug]) }}">
        {{ $service->category->name }}
    </a>
    <span class="text-primary"> / </span>
    <a href="{{ route('user.services.search', ['category_name' => $service->category->slug, 'sub_category_name' => $service->subCategory->slug]) }}">
        {{ $service->subCategory->name }}
    </a>
</div>
<br />

<div class="row">
    <div class="col-md-6">
        <p class="text-dark-65">{{ $service->description }}</p>
    </div>
    @if (!$is_mobile)
        <div class="col-md-6">
            <div id="map-canvas" style="height: 330px; width: 100%; margin-top:0px;" class="margin-top-xs mobile-hide"></div>
        </div>
    @endif
</div>