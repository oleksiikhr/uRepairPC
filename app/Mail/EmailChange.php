<?php declare(strict_types=1);

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
    private $email;

    /**
     * Create a new message instance.
     *
     * @param string $email
     */
    public function __construct(string $email)
    {
        $this->email = $email;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build(): self
    {
        // TODO Translate
        return $this->markdown('emails.users.email')
            ->subject(config('app.name').' - змінився email')
            ->with([
                'email' => $this->email,
            ]);
    }
}
