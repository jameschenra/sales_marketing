<?php

namespace App\Console\Commands;

use App\Models\Book;
use Illuminate\Console\Command;

class OfflineServiceBookingUpdate extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'bookings:offline-service-booking-update';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Processes bookings for offline services.';

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
        $timeForProcessing = date("Y-m-d H:i:s");

        Book::whereNull('delivery_date')    // only offline service
            ->where('status', Book::STATUS_PENDING)
            ->where('book_date', '<', $timeForProcessing)
            ->chunk(100, function ($books) {
                $books->each(function ($book) {
                    $book->status = Book::STATUS_PROVIDED;
                    $book->save();
                });
            }
        );
    }
}
