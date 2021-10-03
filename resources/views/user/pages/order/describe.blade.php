@php
use App\Models\Book;
use App\Models\Service;    
@endphp

@if (!in_array($book->status, [Book::STATUS_PROVIDED, Book::STATUS_COMPLETED, Book::STATUS_CANCEL]))
    <hr>
    <p style="line-height: 1.2;"><i class="font-size-lg">
        @if ($book->provide_online_type == Service::PROVIDE_ONLINE_TYPE)
            {!! trans('main.online.service.text.customer.order.view', [
                'buyer_name' => $book->user->full_name,
            ]) !!}
        @else
            @if ($book->is_paid_online == 1)
                @if ($book->booking_confirm == Service::BOOKING_DIRECTLY)
                    {!! trans('main.customer.order.view.text.service.offline.paid.online.book.directly', [
                        'book_date' => $book->book_day,
                        'book_date_time' => $book->book_time,
                        'buyer_name' => $book->user->full_name
                    ]) !!}
                @else
                    @if ($book->status == Book::STATUS_WAIT_CONFIRM)
                        {!! trans('main.customer.order.view.text.service.offline.paid.online.book.with.confirmation', [
                            'buyer_name' => $book->user->full_name
                        ]) !!}
                    @elseif ($book->status == Book::STATUS_PENDING)
                        {!! trans('main.customer.order.view.text.service.offline.paid.online.book.after.confirmation', [
                            'book_date' => $book->book_day,
                            'book_date_time' => $book->book_time,
                            'buyer_name' => $book->user->full_name
                        ]) !!}
                    @endif
                @endif
            @else
                @if ($book->status == Book::STATUS_WAIT_CONFIRM)
                    {!! trans('main.customer.order.view.text.service.offline.to.pay.on-site.or.free.book.with.confirmation', [
                        'buyer_name' => $book->user->full_name
                    ]) !!}
                @elseif ($book->status == Book::STATUS_PENDING)
                    {!! trans('main.customer.order.view.text.service.offline.to.pay.on-site.or.free.book.after.confirmation', [
                        'book_date' => $book->book_day,
                        'book_date_time' => $book->book_time,
                        'buyer_name' => $book->user->full_name
                    ]) !!}
                @endif
            @endif
        @endif
    </i></p>
@endif