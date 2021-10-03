@php
    $service = $book->service;
    $reviewer = $book->review->reviewer;
@endphp
<div class="detail-review-container">
    <div class="d-flex flex-column flex-sm-row mb-3">
        <div class="service-img-wrapper mr-3" style="min-width: 150px;">
            <img class="border-round" src="{{ HTTP_SERVICE_PATH . $book->service->photo }}" alt="service-logo" />
        </div>

        <div>
            <h3>{{ $book->service->name }}</h3>
            <div class="ml-2">
                <div>
                    @if($reviewer->detail->photo)
                        <img class="user-logo-mini border-round" src="{{ HTTP_USER_PATH . $reviewer->detail->photo }}" alt="user photo">
                    @else
                        <img class="user-logo-mini border-round" src="{{ HTTP_USER_PATH . DEFAULT_PHOTO }}" alt="user photo">
                    @endif
                </div>
                <div>
                    <div class="font-size-h5 font-weight-boldest">
                        {{ $reviewer->initial_name }}&nbsp;
                        @include('user.components.star-rate', ['score' => $book->review->rate])
                    </div>
                </div>
                <div class="mt-2">
                    <div class="show-read-more mobile text-dark-50">{{ $book->review->review }}</div>
                </div>
            </div>
        </div>
    </div>
</div>
<hr />