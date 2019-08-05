<?php

namespace App\Events\RequestTypes;

trait EModel
{
    public static $roomName = 'request_types';

    /**
     * @return string
     */
    public function event(): string
    {
        return 'request_types';
    }
}
