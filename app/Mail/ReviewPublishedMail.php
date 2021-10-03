<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class ReviewPublishedMail extends Mailable
{

    use Queueable,
        SerializesModels;

    protected $reviewer;
    protected $book;
    protected $seller;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($review)
    {
        $this->book = $review->book;
        $this->seller = $review->book->seller;
        $this->reviewer = $review->reviewer;

        if ($this->reviewer->default_language) {
            app()->setLocale($this->reviewer->default_language);
        }
        $this->from(config('mail.from.address'), config('mail.from.name'));
        $this->subject(trans('main.Review published') . $this->seller->name);
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->view('email.review_published', [
            'buyer' => $this->reviewer,
            'seller' => $this->seller,
            'locale' => $this->reviewer->default_language,
        ]);
    }

}
