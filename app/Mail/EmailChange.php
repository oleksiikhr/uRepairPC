<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class EmailChange extends Mailable
{
    use Queueable, SerializesModels;

    /**
     * @var string
     */
    private $_email;

    /**
     * Create a new message instance.
     *
     * @param string $email
     */
    public function __construct(string $email)
    {
        $this->_email = $email;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->markdown('emails.users.email')
            ->subject(config('app.name').' - змінився email')
            ->with([
                'email' => $this->_email,
            ]);
    }
}
