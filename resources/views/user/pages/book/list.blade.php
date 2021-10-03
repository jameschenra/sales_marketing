{{-- Extends layout --}}
@extends('user.layout.default')

@section('styles')
    {{ Html::style(userAsset('pages/book/book.css')) }}
@endsection

@section('content')
<section class="bg-content d-flex align-items-center">
    <div class="container">
        <h3 class="mb-10 font-weight-bold text-dark">@lang('main.My Purchases')</h3>

        @if (count($books) > 0) 
            @foreach ($books as $book)
                @include('user.pages.book.single-book-item', ['service' => $book->service, 'book' => $book])
            @endforeach
            <br />
            <div class="float-right">{{ $books->links() }}</div>
            <div class="clearfix"></div>
        @else
            <div class="col-sm-12 margin-top-normal margin-bottom-normal text-center">
                <div class="note note-info">
                    <h4 class="block">{{trans('main.The cart is empty') }}</h4>
                </div>
            </div>
        @endif
    </div>
</section>
@endsection

@section('scripts')
    <script>
        function onCancelBooking(event, bookId, paidOnline, param, isBeforeBook24Hrs) {
            var cancelButton = $(this);
            var removeUrl = "{{ route('user.book.cancel') }}";
            var popupMsg;

            if (paidOnline == 1) {
                if (isBeforeBook24Hrs == 1) {
                    popupMsg = "{{ trans('main.buyer_cancel_popup_text_online') }}";
                    popupMsg = popupMsg.replaceAll(':refund_price', param);
                } else {
                    popupMsg = "{{ trans('main.buyer_cancel_popup_text_online_with_fee') }}";
                    popupMsg = popupMsg.replaceAll(':fee_price', param);
                }
            } else {
                popupMsg = "{{ trans('main.buyer_cancel_popup_text_offline') }}";
                popupMsg = popupMsg.replaceAll(':seller_name', param);
            }
            Swal.fire({
                title: '<span style="font-size: 16px;">' + popupMsg + '</span>',
                confirmButtonText: "{{ trans('main.confirm_pop-up') }}",
                cancelButtonText: "{{ trans('main.cancel_pop-up') }}",
                showCancelButton: true,
                // customClass: {
                //     confirmButton: "btn btn-primary",
                //     cancelButton: "btn btn-default"
                // }
            }).then(function (result) {
                if (result.value) {
                    ajax_post(
                        removeUrl,
                        { book_id: bookId },
                        function (result) {
                            data = result.data;
                            if (result.data.result == "success") {
                                cancelButton.parents("div.margin-top-xs").eq(0).next().remove();
                                cancelButton.parents("div.margin-top-xs").eq(0).remove();
                                $('.cancel-alert').remove();
                                $('.content-margin-top').after("<div class='container cancel-alert alert alert-" + data.result + "'>" + data.msg + "</div>");
                                window.scrollTo(0, 0);
                                cancelButton.prev().attr('class', 'text-danger book-danger');

                                setTimeout(() => {
                                    // $('.available_services .container .alert').remove();
                                    location.reload();
                                }, 3000);

                                cancelButton.parents(".service-details").find('.book-danger').html('{{trans("main.You canceled")}}');
                                cancelButton.remove();
                            } else {
                                $('.cancel-alert').remove();
                                $('.content-margin-top').after("<div class='container cancel-alert alert alert-danger'>" + data.msg + "</div>");
                                window.scrollTo(0, 0);
                            }
                        }
                    );
                }
            });
        }
    </script>

    {{ Html::script(userAsset('pages/book/book.js')) }}
@endsection
