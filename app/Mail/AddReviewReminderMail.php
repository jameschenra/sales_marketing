<?php

namespace App\Mail;

use App\Models\ReviewToken;
use Illuminate\Support\Str;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;

class AddReviewReminderMail extends Mailable implements ShouldQueue
{

    use Queueable,
        SerializesModels;

    protected $book;
    protected $buyer;
    protected $seller;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($book)
    {
        $this->book = $book;
        $this->buyer = $book->user;
        $this->seller = $book->seller;

        $defaultLang = $this->buyer->default_language;
        if ($defaultLang) {
            app()->setLocale($defaultLang);
        }
        $this->from(config('mail.from.address'), config('mail.from.name'));
        $this->subject(__('email.Review reminder subject') . $this->seller->name . ' ' . $this->seller->surname);
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        $book = $this->book;
        $token = Str::random(40);
        $token = ReviewToken::firstOrCreate(['token' => $token, 'book_id' => $book->id]);

        return $this->view('email.add_review_reminder', [
            'book' => $book,
            'buyer' => $this->buyer,
            'seller' => $this->seller,
            'token' => $token->token,
            'locale' => $this->buyer->default_language,
        ]);
    }

}
