@php
use App\Models\Book;
use App\Models\Service;    
@endphp

<i class="font-size-lg">
    @if ($book->provide_online_type == Service::PROVIDE_ONLINE_TYPE)
        @if (in_array($book->paid_type, [Book::PAID_PAYPAL, Book::PAID_OFFICE]))
            @lang('main.online.service.text.my.purchases', ['seller_name' => $book->seller->name])
        @else
            @lang('main.Cancel free message',[
                'book_date' => $book->book_day,
                'book_date_time' => $book->book_time,
            ])
        @endif
    @else
        @if ($book->is_paid_online == 1)
            @if ($book->booking_confirm == Service::BOOKING_DIRECTLY)
                @lang('main.my-purchases.text.service.offline.paid.online.book.directly', [
                    'book_date' => $book->book_day,
                    'book_date_time' => $book->book_time,
                ])
            @else
                @if ($book->status == Book::STATUS_WAIT_CONFIRM)
                    @lang('main.my-purchases.text.service.offline.paid.online.book.with.confirmation', [
                        'book_date' => $book->book_day,
                        'book_date_time' => $book->book_time,
                    ])
                @else
                    @lang('main.my-purchases.text.service.offline.paid.online.book.directly', [
                        'book_date' => $book->book_day,
                        'book_date_time' => $book->book_time,
                    ])
                @endif
            @endif
        @else
            @if ($book->status == Book::STATUS_WAIT_CONFIRM)
                @lang('main.my-purchases.text.service.offline.to.pay.on-site.or.free.book.with.confirmation', [
                    'book_date' => $book->book_day,
                    'book_date_time' => $book->book_time,
                ])
            @else
                @lang('main.my-purchases.text.service.offline.pay.in.office.or.free.after.confirmation', [
                    'book_date' => $book->book_day,
                    'book_date_time' => $book->book_time,
                ])
            @endif
        @endif
    @endif
</i>