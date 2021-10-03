<?php

namespace App\Console\Commands;

use App\Models\Book;
use App\Models\Service;
use App\Models\TransactionOfBooking;
use Illuminate\Console\Command;

class BalanceChargeProcessing extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'bookings:balance-charge-processing';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Processes balance for pending bookings';

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
        $FOURDAYS = date('Y-m-d H:i:s', time() - 96 * 60 * 60);
        $TWODAYS = date('Y-m-d H:i:s', time() - 48 * 60 * 60);

        // online services provided 5 days after
        $onlineBooks = Book::where('provide_online_type', Service::PROVIDE_ONLINE_TYPE)
            ->where('status', Book::STATUS_PROVIDED)
            ->where('delivery_date', '<', $FOURDAYS)
            ->get();

        // offline services provided 5 days after
        $offlineBooks = Book::where('provide_online_type', Service::PROVIDE_OFFLINE_TYPE)
            ->where('status', Book::STATUS_PROVIDED)
            ->where('book_date', '<', $FOURDAYS)
            ->get();

        $books = $onlineBooks->merge($offlineBooks);

        $books->each(function ($book) {
            $sellerBalance = $book->seller->balance;

            if ($book->is_paid_online == 1) {
                $bookingTransaction = TransactionOfBooking::where([
                    ['transaction_type', '=', TransactionOfBooking::TYPE_NORMAL],
                    ['book_id', '=', $book->id],
                    ['on_hold', '=', 1],
                ])->first();

                if ($bookingTransaction) {
                    TransactionOfBooking::create([
                        'payment_id' => $book->payment_id,
                        'transaction_type' => TransactionOfBooking::TYPE_FEE,
                        'sender_id' => $book->seller_id,
                        'receiver_id' => WEBSITE_WALLET, // the website itself
                        'service_id' => $book->service_id,
                        'amount' => $book->total_fee,
                        'on_hold' => 0,
                        'book_id' => $book->id,
                        'refunded' => 0,
                    ]);

                    $sellerBalance->balance += ($book->total_amount - $book->total_fee);
                    $sellerBalance->pending_balance -= $book->total_amount;
                    $sellerBalance->save();

                    $bookingTransaction->on_hold = 0;
                    $bookingTransaction->save();
                }
            } else {
                $feeTransaction = TransactionOfBooking::where([
                    ['transaction_type', '=', TransactionOfBooking::TYPE_FEE],
                    ['book_id', '=', $book->id],
                    ['on_hold', '=', 1],
                ])->first();
    
                if ($feeTransaction) {
                    $feeTransaction->on_hold = 0;
                    $feeTransaction->save();
                }
            }

            $book->status = Book::STATUS_COMPLETED;
            $book->save();
        });
    }
}
