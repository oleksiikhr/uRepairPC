<?php

declare(strict_types=1);

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class UserCreated extends Mailable
{
    use Queueable, SerializesModels;

    /**
     * @var string
     */
    private string $password;

    /**
     * Create a new message instance.
     *
     * @param string $password
     */
    public function __construct(string $password)
    {
        $this->queue = 'email';
        $this->connection = 'database';
        $this->password = $password;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build(): self
    {
        // TODO Translate
        return $this->markdown('emails.users.password')
            ->subject(config('app.name').' - Вас додали в систему')
            ->with([
                'password' => $this->password,
            ]);
    }
}
