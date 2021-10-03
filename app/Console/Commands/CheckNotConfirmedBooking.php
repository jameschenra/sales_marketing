<?php

namespace App\Console\Commands;

use App\Models\Book;
use App\Models\Service;
use App\Models\TransactionOfBooking;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Mail;

class CheckNotConfirmedBooking extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'bookings:check-not-confirmed-booking';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Cancel booking not confirmed';

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
     * If the booking not confirmed by seller before 48hrs, it cancel the booking automatically
     * @return int
     */
    public function handle()
    {
        Book::where('booking_confirm', Service::BOOKING_CONFIRM)
            ->where('status', Book::STATUS_WAIT_CONFIRM)
            ->where('created_at', '<', date('Y-m-d H:i:s', time() - 48 * 60 * 60))
            ->chunk(100, function ($books) {
                $books->each(function ($book) {
                    $this->cancelBooking($book);
                });
            }
        );
    }

    private function cancelBooking($book)
    {
        try {
            $refundTransaction = null;

            // Refund the payment to the buyer
            $transaction = TransactionOfBooking::where([
                'transaction_type' => TransactionOfBooking::TYPE_NORMAL,
                'on_hold' => 1,
                'sender_id' => $book->user_id,
                'receiver_id' => $book->seller_id,
                'service_id' => $book->service_id,
                'book_id' => $book->id,
            ])->first();

            // This will be null if Pay to office booking
            if ($transaction) { // if the transaction exists then create the reverse for a refund
                $refundTransaction = TransactionOfBooking::create([
                    'transaction_type' => TransactionOfBooking::TYPE_NORMAL_REFUND,
                    'sender_id' => $transaction->receiver_id,
                    'receiver_id' => $transaction->sender_id,
                    'service_id' => $transaction->service_id,
                    'amount' => $transaction->amount,
                    'on_hold' => 0,
                    'book_id' => $book->id,
                ]);

                // Also set the original transaction as not on hold
                $transaction->on_hold = 0;
                $transaction->refunded = 1;
                $transaction->save();

                $userBalance = $book->user->balance;
                $userBalance->balance += $transaction->amount;
                $userBalance->save();

                $sellerBalance = $book->seller->balance;
                $sellerBalance->pending_balance -= $transaction->amount;
                $sellerBalance->save();
            }

            $book->status = Book::STATUS_CANCEL;
            $book->deleted_by = 'auto';
            $book->save();

            $param = [
                'book' => $book,
                'bookingTransaction' => $refundTransaction,
                'feeTransaction' => null,
            ];

            $info2User = [
                'reply_name' => REPLY_NAME,
                'reply_email' => NOREPLY_EMAIL,
                'email' => $book->user->email,
                'name' => $book->user->name,
                'subject' => trans('main.email.booking.cancelAutomatic.buyer.subject', ['seller_name' => $book->seller->name], $book->user->default_language),
            ];

            $info2Professional = [
                'reply_name' => REPLY_NAME,
                'reply_email' => NOREPLY_EMAIL,
                'email' => $book->seller->email,
                'name' => $book->seller->name,
                'subject' => trans('main.email.booking.cancelAutomatic.seller.subject', ['buyer_name' => $book->user->name], $book->seller->default_language),
            ];

            Mail::send('email.auto-cancel-book-buyer.' . $book->user->default_language, $param, function ($message) use ($info2User) {
                $message->from($info2User['reply_email'], $info2User['reply_name']);
                $message->to($info2User['email'], $info2User['name'])->subject($info2User['subject']);
            });

            Mail::send('email.auto-cancel-book-professional.' . $book->seller->default_language, $param, function ($message) use ($info2Professional) {
                $message->from($info2Professional['reply_email'], $info2Professional['reply_name']);
                $message->to($info2Professional['email'], $info2Professional['name'])
                    ->subject($info2Professional['subject']);
            });
        } catch (\Exception $e) {
        }
    }
}
