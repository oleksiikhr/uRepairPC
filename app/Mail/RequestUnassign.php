<?php

declare(strict_types=1);

namespace App\Mail;

use App\Models\Request;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class RequestUnassign extends Mailable
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
        return $this->markdown('emails.requests.unassign')
            ->subject(config('app.name').' - Замовлення відкріплено (#'.$this->request->id.')')
            ->with([
                'request' => $this->request,
            ]);
    }
}
