<?php

declare(strict_types=1);

namespace App\Realtime\Job;

trait EModel
{
    public static string $roomName = 'jobs';

    /**
     * @return string
     */
    public function event(): string
    {
        return 'jobs';
    }
}
