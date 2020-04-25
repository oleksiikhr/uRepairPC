<?php declare(strict_types=1);

namespace App\Events\Requests;

trait EModel
{
    public static string $roomName = 'requests';

    /**
     * @return string
     */
    public function event(): string
    {
        return 'requests';
    }
}
