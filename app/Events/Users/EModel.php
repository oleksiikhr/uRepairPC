<?php

namespace App\Events\Users;

trait EModel
{
    public static $roomName = 'users';

    /**
     * @return string
     */
    public function event(): string
    {
        return 'users';
    }
}
