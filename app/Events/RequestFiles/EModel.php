<?php declare(strict_types=1);

namespace App\Events\RequestFiles;

trait EModel
{
    public static string $roomName = 'request_files';

    /**
     * @return string
     */
    public function event(): string
    {
        return 'request_files';
    }
}
