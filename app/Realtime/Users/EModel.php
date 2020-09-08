<?php

declare(strict_types=1);

namespace App\Realtime\Users;

trait EModel
{
    public static string $roomName = 'users';

    /**
     * @return string
     */
    public function event(): string
    {
        return 'users';
    }
}
