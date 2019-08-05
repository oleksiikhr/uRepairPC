<?php

namespace App\Events\RequestComments;

trait EModel
{
    public static $roomName = 'request_comments';

    /**
     * @return string
     */
    public function event(): string
    {
        return 'request_comments';
    }
}
