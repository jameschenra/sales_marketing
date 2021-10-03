@php
    use App\Models\Book;
    use App\Models\RequestExtendDeliveryDate;
    use App\Models\RequestModification;

    $buyer = $book->user;
    $seller = $book->seller;

    $extendRequest = $book->extend_request;
    $isExtendRequested = false;
    $isExtendDeclined = false;
    if ($extendRequest) {
        if ($extendRequest->status == RequestExtendDeliveryDate::STATUS_EXTEND_REQUEST) {
            $isExtendRequested = true;
        }

        if ($extendRequest->status == RequestExtendDeliveryDate::STATUS_EXTEND_DENIED) {
            $isExtendDeclined = true;
        }
    }
    
    $modifyRequest = $book->modification_request;
    $isModifyRequested = false;
    $isModifyExtended = false;
    if ($modifyRequest) {
        if ($modifyRequest->status == RequestModification::STATUS_MODIFY_REQUEST) {
            $isModifyRequested = true;
        }

        if ($modifyRequest->status == RequestModification::STATUS_MODIFY_ACCEPT_EXTEND) {
            $isModifyExtended = true;
        }
    }
@endphp

<div class="file-list">
    <div class="card shadow rounded border-0 mt-4">
        <div class="card-body">
            <div class="d-flex justify-content-between">
                <h5 class="card-title mb-0">{{ trans("main.Messages and Files")}}</h5>
                <h5 class="card-title mb-0">{{ trans("main.Current delivery date")}} <span style="font-weight: normal">{{ date('d/m/Y', strtotime($book->delivery_date)) }}</span></h5>
            </div>
            
            <ul class="media-list list-unstyled mb-0">
                @forelse ($book->onlineOrderMessages as $message)
                    @php
                        $author = $message->buyer_id ? $buyer : $seller;
                        $photo = $message->buyer_id ? 'default.png' : $author->detail->photo;
                    @endphp
                    <li class="mt-4">
                        <div class="d-flex justify-content-between">
                            <div class="media align-items-center">
                                <a class="pr-3" href="#">
                                    <img src="{{ HTTP_USER_PATH . $photo }}" class="img-fluid avatar avatar-md-sm rounded-circle shadow" alt="img">
                                </a>
                                <div class="commentor-detail">
                                    <h6 class="mb-0"><a href="javascript:void(0)" class="text-dark media-heading">{{ $author->initial_name }}</a></h6>
                                    <small class="text-muted">{{ date('d/m/Y H:m', strtotime($message->created_at)) }}</small>
                                </div>
                            </div>
                            @if ($message->file_path)
                                <a href="{{ HTTP_ONLINE_FILE_PATH . $message->file_path }}" target="_blank"><i class="mdi mdi-file"></i> {{ $message->file_name }}</a>
                            @endif
                        </div>
                        @if ($message->message)
                            <div class="mt-3">
                                <p class="text-muted font-italic p-3 bg-light rounded">{!! $message->message !!}</p>
                            </div>
                        @endif
                    </li>
                @empty
                    <li>{{ trans("main.empty")}}</li>
                @endforelse
            </ul>
            
            {{-- sending message and request --}}
            @if (!(in_array($book->status, [Book::STATUS_PROVIDED, Book::STATUS_COMPLETED, Book::STATUS_CANCEL])))
                <br />
                <hr />
                <form id="chat-message-form" method="POST" class="mt-8" enctype="multipart/form-data">
                    @csrf
                    <input type="hidden" name="book_id" value="{{ $book->id }}" />
                    <input type="hidden" name="user_type" value="{{ $user_type }}" />
                    <input type="hidden" name="online_service_processing" value="1" />

                    <div class="form-group position-relative">
                        <textarea placeholder="{{ trans('main.Your Comment here')}}" rows="3" name="message" class="form-control pl-5" required></textarea>
                    </div>

                    <div class="d-flex flex-column flex-sm-row justify-content-between">
                        <div class="form-group position-relative">
                            <label>{{ trans("main.Choose file")}}</label>
                            <input type="file" name="online_file" />
                            <a href="#" class="d-none btn btn-icon btn-pills btn-primary"><i data-feather="plus" class="fea"></i></a>
                        </div>

                        <div class="send text-right">
                            {{-- if buyer --}}
                            @if ($user_type == 'buyer')
                                @if ($isExtendRequested)
                                    <input type="hidden" name="delivery_extend_request_id" value="{{ $extendRequest->id }}" />
                                    <button type="submit" class="btn btn-danger btn-responsive mt-2 mr-0 mr-sm-4"
                                        data-action-url="{{ route('user.book.cancel-extend') }}">{{ trans("main.Disagree")}}</button>
                                    <button type="submit" class="btn btn-primary btn-responsive mt-2" data-no-validation="1"
                                        data-action-url="{{ route('user.book.accept-extend') }}">{{ trans("main.Agree")}}</button>
                                @else
                                    @if (time() > strtotime($book->delivery_date))
                                        @if (!$isModifyRequested)
                                            <button type="submit" class="btn btn-light-primary book-action-btn btn-responsive mt-4 mr-0 mr-sm-4"
                                                data-action-url="{{ route('user.book.request-modify') }}">{{ trans('main.Request modification') }}</button>
                                        @endif
                                        
                                        <button type="submit" class="btn btn-primary book-action-btn btn-responsive mt-4" data-no-validation="1"
                                            data-action-url="{{ route('user.book.accept-result') }}">{{ trans('main.Accept and confirm') }}</button>
                                    @else
                                        <button type="submit" class="btn btn-primary btn-responsive"
                                            data-action-url="{{ route('user.book.send-message') }}">{{ trans("main.Send Message") }}</button>
                                    @endif

                                    @if ($isModifyExtended)
                                        <a href="{{ route('user.contact-us') }}"
                                            class="btn btn-light-primary btn-responsive mt-4 mt-sm-4">@lang('main.Contact-support-button')</a>
                                    @endif
                                @endif
                            @endif

                            {{-- is seller --}}
                            @if ($user_type == 'seller')
                                @if (!$isModifyRequested && !$isExtendRequested
                                    && (time() < strtotime($book->delivery_date)))
                                    <button type="button" class="btn btn-primary btn-responsive mr-0 mr-sm-4"
                                        data-toggle="modal" data-target="#extend-delivery-date-modal">{{ trans("main.Request Extend") }}</button>
                                @endif

                                @if ($isModifyRequested)
                                    <button type="submit" class="btn btn-primary btn-responsive mb-4 mr-0 mr-sm-4" data-no-validation="1"
                                        data-action-url="{{ route('user.orders.accept-modify') }}">{{ trans("main.Accept request") }}</button>
                                    <button type="button" class="btn btn-primary btn-responsive mb-4 mr-0 mr-sm-4"
                                        data-toggle="modal" data-target="#extend-delivery-date-modal">{{ trans("main.Accept request and extend date") }}</button>
                                    <button type="button" class="btn btn-danger btn-responsive mb-4 mr-0 mr-sm-4"
                                        onclick="onCancelOnlineOrder(event)"
                                        data-action-url="{{ route('user.orders.cancel') }}">{{ trans("main.Cancel order") }}</button>
                                @else
                                    @if ($isExtendDeclined)
                                        <button type="button" class="btn btn-danger btn-responsive mr-0 mr-sm-4"
                                            onclick="onCancelOnlineOrder(event)"
                                            data-action-url="{{ route('user.orders.cancel') }}">{{ trans("main.Cancel order") }}</button>
                                    @endif
                                    <button type="submit" class="btn btn-primary btn-responsive mt-4 mt-sm-0"
                                        data-action-url="{{ route('user.orders.send-message') }}">{{ trans("main.Send Message") }}</button>
                                @endif
                            @endif
                        </div>
                    </div>
                    
                    <div class="form-group mt-4">
                        <div class="custom-control custom-checkbox">
                            <input type="checkbox" id="terms-agree" name="terms_agree" class="custom-control-input" required>
                            <label class="custom-control-label" for="terms-agree">@lang('main.Check box is online')</label>
                        </div>
                    </div>
                </form><!--end form-->
            @endif
            {{--./ sending message and request --}}
        </div>
    </div>
</div>

{{-- request extension date --}}
@if ($user_type == 'seller' &&
    !(in_array($book->status, [Book::STATUS_PROVIDED, Book::STATUS_COMPLETED, Book::STATUS_CANCEL])))
    <div class="modal fade" id="extend-delivery-date-modal" tabindex="-1" role="dialog">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="card-title mb-0">{{ trans("main.Select new delivery date")}}</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <i aria-hidden="true" class="ki ki-close"></i>
                    </button>
                </div>

                <div class="modal-body">
                    <form action="{{ $modifyRequest ? route('user.orders.accept-modify-extend') : route('user.orders.request-extend') }}"
                        method="POST" id="form-extend-delivery-date">
                        @csrf
                        <input type="hidden" name="book_id" value="{{ $book->id }}" />
    
                        <div class="form-group position-relative">
                            <div class="date-picker"></div>
                            <input type="hidden" name="new_delivery_date" id="new-delivery-date" />
                        </div>

                        <div class="form-group">
                            <textarea name="extend_reason" class="form-control" required
                                placeholder="{{ trans('main.write reason for extend delivery date') }}"></textarea>
                        </div>
                            
                        <div class="send text-right">
                            <button type="button" class="btn btn-primary btn-responsive" onclick="submitExtendDelivery(event)">{{ trans("main.Request Extend")}}</button>
                            <button type="submit" class="d-none"></button>
                        </div>        
                    </form><!--end form-->    
                </div>
            </div>
        </div>
    </div>
@endif
{{--./ request extension date --}}