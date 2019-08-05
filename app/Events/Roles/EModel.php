<?php

namespace App\Events\Roles;

trait EModel
{
    public static $roomName = 'roles';

    /**
     * @return string
     */
    public function event(): string
    {
        return 'roles';
    }
}
