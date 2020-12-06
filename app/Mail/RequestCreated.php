<?php

declare(strict_types=1);

namespace App\Mail;

use App\Models\Request;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class RequestCreated extends Mailable
{
    use Queueable, SerializesModels;

    /**
     * @var Request
     */
    private Request $request;

    /**
     * Create a new message instance.
     *
     * @param Request $request
     */
    public function __construct(Request $request)
    {
        $this->queue = 'email';
        $this->connection = 'database';
        $this->request = $request;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build(): self
    {
        // TODO Translate
        return $this->markdown('emails.requests.created')
            ->subject(config('app.name').' - Нове замовлення')
            ->with([
                'request' => $this->request,
            ]);
    }
}
