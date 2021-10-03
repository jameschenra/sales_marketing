<?php

namespace App\Console\Commands;

use App\Mail\AddReviewReminderMail;
use App\Models\Book;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Mail;

class SendAddReviewReminder extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'reviews:add-review-reminder';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Send email with suggestion to add review';

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
     *
     * @return int
     */
    public function handle()
    {
        // past 1 day ~ 4 days after book date for offline service
        $offlineBooks = Book::with('user')
            ->whereNull('delivery_date')
            ->whereNull('review_id')
            ->whereNull('deleted_by')
            ->whereRaw('(unix_timestamp(book_date)+86400)<unix_timestamp()')
            ->whereRaw('(unix_timestamp(book_date)+345600)>unix_timestamp()')
            ->get();

        // past 1 day ~ 4 days after book date for online service
        $onlineBooks = Book::with('user')
            ->whereNotNull('delivery_date')
            ->where('status', Book::STATUS_PROVIDED)
            ->whereNull('review_id')
            ->whereNull('deleted_by')
            ->whereRaw('(unix_timestamp(accepted_date)+86400)<unix_timestamp()')
            ->whereRaw('(unix_timestamp(accepted_date)+345600)>unix_timestamp()')
            ->get();

        $books = $offlineBooks->merge($onlineBooks);

        $books->each(function ($item, $key) {
            Mail::to($item->user->email, $item->user->name)->queue(new AddReviewReminderMail($item));
        });
    }
}
