<?php

namespace App\Mail;

use App\User;
use App\Models\TFA;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;

class TFAMail extends Mailable
{
    use Queueable, SerializesModels;
    /**
     * @var User
     */
    private $user;

    /**
     * @var TFA
     */
    public $code;

    /**
     * @var string
     */
    public $locale;

    /**
     * Create a new message instance.
     *
     * @param User $user
     * @param TFA $code
     * @param $locale
     */
    public function __construct(User $user, TFA $code, $locale)
    {
        $this->user = $user;
        $this->code = $code;
        $this->locale = $locale;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        if ($this->code->type != TFA::WITHDRAW)
            $subject = trans('main.TFA.Your verification code to booking');
        else
            $subject = trans('main.TFA.Your verification code to withdraw');
        return $this->view('email.TFA_email_' . $this->locale, ['user' => $this->user])
            ->to($this->user->email)
            ->subject($subject);
    }
}
