<?php

namespace App\Events\RequestStatuses;

trait EModel
{
    public static $roomName = 'request_statuses';

    /**
     * @return string
     */
    public function event(): string
    {
        return 'request_statuses';
    }
}
