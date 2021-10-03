<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class PasswordChangedMail extends Mailable
{
    use Queueable, SerializesModels;
    /**
     * @var string
     */
    private $lang;
    private $user;

    /**
     * Create a new message instance.
     *
     * @param string $lang
     * @param $user
     */
    public function __construct(string $lang, $user)
    {
        $this->lang = $lang;
        $this->user = $user;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this
            ->subject(__('main.email.password_changed.subject'))
            ->view('email.password_changed_' . $this->lang, [
                'username' => $this->user->name . ' ' . $this->user->surname,
            ]);
    }
}
