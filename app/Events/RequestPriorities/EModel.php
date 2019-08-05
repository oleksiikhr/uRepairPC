<?php

namespace App\Events\RequestPriorities;

trait EModel
{
    public static $roomName = 'request_priorities';

    /**
     * @return string
     */
    public function event(): string
    {
        return 'request_priorities';
    }
}
