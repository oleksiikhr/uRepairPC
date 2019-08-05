<?php

namespace App\Events\Requests;

trait EModel
{
    public static $roomName = 'requests';

    /**
     * @return string
     */
    public function event(): string
    {
        return 'requests';
    }
}
