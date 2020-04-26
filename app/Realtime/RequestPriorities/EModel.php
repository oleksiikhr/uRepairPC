<?php declare(strict_types=1);

namespace App\Realtime\RequestPriorities;

trait EModel
{
    public static string $roomName = 'request_priorities';

    /**
     * @return string
     */
    public function event(): string
    {
        return 'request_priorities';
    }
}
