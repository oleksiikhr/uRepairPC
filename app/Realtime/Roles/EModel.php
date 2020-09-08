<?php

declare(strict_types=1);

namespace App\Realtime\Roles;

trait EModel
{
    public static string $roomName = 'roles';

    /**
     * @return string
     */
    public function event(): string
    {
        return 'roles';
    }
}
