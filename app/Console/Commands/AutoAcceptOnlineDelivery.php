<?php

namespace App\Console\Commands;

use App\Models\Book;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Mail;

class AutoAcceptOnlineDelivery extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'bookings:auto-accept-online-delivery';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Accept online delivery automatically if not confirmed in 48hrs';

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
            ->where('delivery_date', '<', date('Y-m-d H:i:s', time()- 48 * 60 * 60))
            ->get();

        $books->each(function ($book, $key) {
            $book->status = Book::STATUS_PROVIDED;
            $book->notify_status = Book::NOTIFY_STATUS_AUTO_ACCEPT;
            $book->save();

            $defaultLang = $book->user->default_language;
            Mail::send('email.online-service-to-buyer-for-order-auto-completed.' . $defaultLang,
                ['book' => $book],
                function ($message) use ($book, $defaultLang) {
                    $message->from(NOREPLY_EMAIL, REPLY_NAME);
                    $message->to($book->user->email, $book->user->name)
                        ->subject(trans('main.Online.service.to.buyer.for.order.auto.completed.object', [], $defaultLang));
                }
            );

            $defaultLang = $book->seller->default_language;
            Mail::send('email.online-service-buyer-accept.' . $defaultLang,
                ['book' => $book],
                function ($message) use ($book, $defaultLang) {
                    $message->from(NOREPLY_EMAIL, REPLY_NAME);
                    $message->to($book->seller->email, $book->seller->name)
                        ->subject(trans('main.Online service accepted by customer', ['buyer_name' => $book->user->name], $defaultLang));
                }
            );
        });
    }
}
