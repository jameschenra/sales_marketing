<?php

namespace App\Console\Commands;

use App\Models\Book;
use App\Models\OnlineOrderMessage;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;
use App\Models\RequestExtendDeliveryDate;

class AutoRequestExtendDeliveryDate extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'bookings:request-extend-delivery-date';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Processes request extend delivery date.';

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
        $extendRequests = RequestExtendDeliveryDate::where('status', RequestExtendDeliveryDate::STATUS_EXTEND_REQUEST)
            ->whereRaw('(unix_timestamp(delivery_date)+86400)<unix_timestamp()')
            ->groupBy('book_id')
            ->get('book_id', DB::raw('MAX(id)'), DB::raw('MAX(delivery_date) as delivery_date'));

        $extendRequests->each(function ($extendRequest) {
            $book = Book::find($extendRequest->book_id);
            if ($book) {
                $defaultLang = $book->seller->default_language;
                $param = [
                    'book' => $book,
                    'new_delivery_date' => $extendRequest->delivery_date
                ];
                Mail::send('email.delivery-date-extend-accept-automatically.' . $defaultLang, $param, function ($message) use ($book) {
                    $message->from(NOREPLY_EMAIL, REPLY_NAME);
                    $message->to($book->seller->email, $book->seller->name)
                        ->subject(trans('main.email.delivery.date.extend.accept.automatically', [], $defaultLang));
                });

                $model = new OnlineOrderMessage;
                $model->book_id = $book->id;
                $model->buyer_id = $book->user_id;
                $model->message = trans('main.Extend request accepted automatically');
                $model->save();

                RequestExtendDeliveryDate::where('book_id', $book->id)
                    ->update(['status' => RequestExtendDeliveryDate::STATUS_EXTEND_ACCEPT]);
            } else {
                RequestExtendDeliveryDate::where('book_id', $extendRequest->book_id)->delete();
            }
        });
    }
}
