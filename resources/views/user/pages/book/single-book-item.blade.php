@php
    use App\Models\Book;
    use App\Models\Service;

    $user = $service->user;
    if ($service->active && ($service->price > 0 || $user->unsubscribe_minimum_credit == 1)){
        $href = route('user.service.show-booking', ['slug' => $service->slug]);
    } else {
        $href = 'javascript:swal.fire({text: "'.trans('main.The service is not available at the moment').'"});';
    }

    $subCategory = $service->subCategory;
    if (! is_null($subCategory)) {
        $link = route('user.services.search', [
            'category_name'     => $subCategory->category->slug,
            'sub_category_name' => $subCategory->slug
        ]);
    } else {
        $link = 'javascript:void(0);';
    }
@endphp

<div class="book-single-item">
    @if(!isset($is_detail))
    <div>
        <h3 class="d-inline-block mr-8">{{ $service->name }}</h3>
    </div>
    @endif
    <div class="row">
        {{-- detail column --}}
        <div class="col-md-10">
            <div class="row">
                {{-- left column --}}
                <div class="col-md-6">
                    <p>
                        <span class="text-dark font-weight-boldest">@lang('main.Order no')</span> {{ $book->id }}
                        <span class="text-dark font-weight-boldest">@lang('main.on')</span> {{ date('d/m/Y', strtotime($book->created_at)) }}
                        <span class="text-dark font-weight-boldest">@lang('main.at')</span> {{ date('H:i', strtotime($book->created_at)) }}
                    </p>

                    <p>
                        @switch($book->paid_type)
                            @case(Book::PAID_PAYPAL)
                                <span class="font-weight-boldest">@lang('main.Price paid paypal'):</span> € {{ number_format($book->total_amount, 2) }}
                                @break
                            @case(Book::PAID_CREDIT)
                                <span class="font-weight-boldest">@lang('main.Price paid balance buyer'):</span> € {{ number_format($book->total_amount, 2) }}
                                @break

                            @case(Book::PAID_OFFICE)
                                <?php $isOffice = true; ?>
                                <span class="font-weight-boldest">@lang('main.Price paid office'):</span> € {{ number_format($book->total_amount, 2) }}
                                @break

                            @case(Book::PAID_FREE)
                                <?php $isFree = true; ?>
                                <strong>{!! trans('main.Price paid free') !!}</strong>
                                @break
                        @endswitch
                    </p>

                    <p>
                        @isset($book->clientTransaction)
                            <span class="font-weight-boldest">@lang('main.Payment transaction No')</span> {{ $book->clientTransaction->id }}
                        @endisset
                    </p>

                    @if ($book->provide_online_type != Service::PROVIDE_ONLINE_TYPE
                        && $book->status != Book::STATUS_WAIT_CONFIRM
                        && $book->status != Book::STATUS_CANCEL
                    )
                        <p><span class="font-weight-boldest">@lang('main.Contact details of')</span> {{ $book->seller->full_name }}</p>

                        <p><span class="font-weight-boldest">@lang('main.Phone'):</span> {{ $book->seller->phone }}, 
                            @if($book->office)
                                <span class="font-weight-boldest">@lang('main.Office telephone'):</span> {{ $book->office->phone_number }}
                            @endif
                        </p>

                        <p><span class="font-weight-boldest">@lang('main.Email'):</span> {{ $book->seller->email }}</p>
                    @elseif ($book->status == Book::STATUS_WAIT_CONFIRM)
                        <p class="text-muted font-size-lg">@lang("main.After confirmation you'll receive contact details of", ['seller_name' => $book->seller->name])</p>
                    @endif
                </div>
                {{--./ left column --}}

                {{-- middle column --}}
                <div class="col-md-6 detail-middle">
                    <p><span class="font-weight-boldest">@lang('main.Services ordered'):</span> {{ $book->number_of_booking }}</p>
                    @if ($book->provide_online_type == Service::PROVIDE_ONLINE_TYPE)
                        <p><span class="font-weight-boldest">@lang('main.Your Delivery date'):</span> {{date('d/m/Y', strtotime($book->delivery_date))}}</p>
                        <p><span class="font-weight-boldest">@lang('main.Working days'):</span> {{ $book->service->online_delivery_time }}</p>
                        <p><span class="font-weight-boldest">@lang('main.Numbers-of-revisions-included'):</span> {{ $book->online_revision == -1 ? trans('main.Unlimited revisions') : $book->online_revision }}</p>
                        <p>
                            <span class="font-weight-boldest">@lang('main.Place from where the service will be provided'):</span> 
                            {{ $book->office->city->name }}
                        </p>
                    @else
                        <p><span class="font-weight-boldest">@lang('main.Booking Date'):</span> {{date('d/m/Y', strtotime($book->book_date))}}
                            <span class="font-weight-boldest">@lang('main.at')</span> {{date('H:i', strtotime($book->book_date))}}</p>
                        <p><span class="font-weight-boldest">@lang('main.Duration'):</span> {{ $book->prettyDuration }}</p>

                        @if ($book->status != Book::STATUS_CANCEL)
                            <p><span class="font-weight-boldest">@lang('main.Location to provide service buyer'):</span> 
                                @if ($book->user_address)
                                    @lang('main.Address buyer typed')
                                @else
                                    {{$book->office->city->name}}
                                @endif
                            </p>
        
                            <p><span class="font-weight-boldest">@lang('main.Address'):</span> 
                                @if ($book->user_address)
                                    {{ $book->user_address }}
                                @else
                                    @if ($book->status == Book::STATUS_WAIT_CONFIRM)
                                        @lang('main.Address will be displayed after confirmation')
                                    @else
                                        {{ $book->office->full_address }}
                                    @endif
                                @endif
                            </p>
                        @endif
                    @endif
                </div>
                {{-- ./middle column --}}

                 @if($book->provide_online_type != Service::PROVIDE_ONLINE_TYPE && !empty($book->message))
                    <div class="mb-2 col-md-12">
                        <span class="font-weight-boldest">@lang('main.Message sent during the reservation'):</span>
                        <br />
                        {{ $book->message }}
                    </div>
                @endif

                <div class="mobile-hide col-md-12">
                    @if ($book->status == Book::STATUS_PENDING || $book->status == Book::STATUS_WAIT_CONFIRM)
                        <p>@include('user.pages.book.book-item-describe')</p>
                    @endif
                </div>
            </div>
        </div>
        {{-- ./detail column --}}
        
        {{-- right column --}}
        <div class="col-md-2 text-center">
            <div class="mb-2">
                <a href="{{ $link }}">
                    @if($service->photo)
                        <img class="service-logo-mini border-round" src="{{ HTTP_SERVICE_PATH . $service->photo }}" alt="user photo">
                    @else
                        <img class="service-logo-mini border-round" src="{{ HTTP_SERVICE_PATH . DEFAULT_PHOTO }}" alt="user photo">
                    @endif
                </a>
            </div>

            <div>
                <div class="font-size-h4">{{ trans('main.Service Status')}}</div>

                @if ($book->status == Book::STATUS_CANCEL)
                    <div class="text-danger font-size-h4 font-weight-bolder">
                        @if($book->deleted_by == 'buyer') {{ trans('main.You canceled') }}
                        @elseif($book->deleted_by == 'admin') {{ trans('main.Canceled by Administrator') }}
                        @elseif($book->deleted_by == 'auto') {{ trans('main.Canceled, no confirmation') }}
                        @else {{trans('main.Canceled by Seller')}}
                        @endif
                    </div>
                @elseif ($book->status == Book::STATUS_PROVIDED || $book->status == Book::STATUS_COMPLETED)
                    <div class="text-primary font-size-h4 font-weight-bolder">{{trans('main.Provided')}}</div>
                @elseif ($book->status == Book::STATUS_WAIT_CONFIRM)
                    <div class="text-warning font-size-h4 font-weight-bolder">{{trans('main.Waiting Accept')}}</div>
                @else
                    <div class="text-warning font-size-h4 font-weight-bolder">{{trans('main.Pending')}}</div>
                @endif

                @if ($book->provide_online_type == Service::PROVIDE_OFFLINE_TYPE && $book->cancellableByUser())
                    @php
                        if ($book->is_paid_online == 1) {
                            if ($book->isBeforeBook24Hrs()) {
                                $cancelParam = $book->total_amount;
                            } else {
                                $cancelParam = round($book->total_amount / 2, 2);
                            }
                        } else {
                            $cancelParam = $book->seller->name;
                        }
                    @endphp

                    <button class="btn btn-danger btn-responsive book-action-btn mt-4" type="button"
                        onclick="onCancelBooking(event, {{ $book->id }}, {{ $book->is_paid_online }}, '{{ $cancelParam }}', '{{ $book->isBeforeBook24Hrs() }}')">
                            {{trans('main.Cancel booking')}}
                    </button>
                @endif

                @if ($book->status == Book::STATUS_PENDING || $book->status == Book::STATUS_WAIT_CONFIRM)
                    <p class="mt-4 desktop-hide smaller">@include('user.pages.book.book-item-describe')</p>
                @endif

                @if ($book->status == Book::STATUS_PROVIDED || $book->status == Book::STATUS_COMPLETED)
                    <?php
                        if ($book->service->active) {
                            $rebookLink = route('user.service.show-booking', ['slug' => $book->service->slug]);
                        } else {
                            $rebookLink = 'javascript:swal.fire({text: "' . trans('main.The service is not available at the moment') . '"});';
                        }
                    ?>
                    <a class="rebook-service-btn btn btn-primary btn-responsive book-action-btn mt-4" data-id="{{ $book->id }}" href="{{ $rebookLink }}">
                        @if ($book->provide_online_type == Service::PROVIDE_ONLINE_TYPE)
                            {{ trans('main.Order again') }}
                        @else
                            {{ trans('main.Book again') }}
                        @endif
                    </a>
                @endif

                @if ($book->provide_online_type == Service::PROVIDE_ONLINE_TYPE && !isset($is_detail))
                    <a class="btn btn-light-primary btn-responsive book-action-btn mt-2" href="{{ route('user.book.detail', ['id' => $book->id]) }}">@lang("main.View Detail")</a>
                @endif
            </div>
        </div>
        {{-- ./right column --}}
    </div>
</div>