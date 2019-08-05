<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class PasswordReset extends Mailable
{
    use Queueable, SerializesModels;

    /**
     * @var string
     */
    private $_password;

    /**
     * Create a new message instance.
     *
     * @param string $password
     */
    public function __construct(string $password)
    {
        $this->_password = $password;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->markdown('emails.users.password')
            ->subject(config('app.name').' - змінився пароль')
            ->with([
                'password' => $this->_password,
            ]);
    }
}
