<?php declare(strict_types=1);

namespace App\Realtime\RequestComments;

trait EModel
{
    public static string $roomName = 'request_comments';

    /**
     * @return string
     */
    public function event(): string
    {
        return 'request_comments';
    }
}
