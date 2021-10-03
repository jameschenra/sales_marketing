<?php

namespace App\Console\Commands;

use App\Models\Book;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Mail;

class SendRequestConfirmOnlineDelivery extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'bookings:send-request-confirm-online-delivery';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Send request confirm online delivery.';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     * If the offline service's book_date passed, then book status is updated to provided
     * @return int
     */
    public function handle()
    {
        $books = Book::whereNotNull('delivery_date')
            ->where('status', Book::STATUS_PENDING)
            ->where('delivery_date', '<', date('Y-m-d H:i:s', time()))
            ->where('notify_status', Book::NOTIFY_STATUS_NONE)
            ->get();

        $books->each(function ($book, $key) {
            $defaultLang = $book->user->default_language;
            Mail::send('email.online-service-completed-buyer-need-confirm.' . $defaultLang,
                ['book' => $book],
                function ($message) use ($book, $defaultLang) {
                    $message->from(NOREPLY_EMAIL, REPLY_NAME);
                    $message->to($book->user->email, $book->user->name)
                        ->subject(trans('main.email.order.completed.buyer.need.confirm.object',
                            ['buyer_name' => $book->user->name], $defaultLang));
                }
            );

            $book->notify_status = Book::NOTIFY_STATUS_REQUEST_CONFIRM;
            $book->save();
        });
    }
}
