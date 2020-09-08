<?php

declare(strict_types=1);

namespace App\Realtime\RequestTypes;

trait EModel
{
    public static string $roomName = 'request_types';

    /**
     * @return string
     */
    public function event(): string
    {
        return 'request_types';
    }
}
