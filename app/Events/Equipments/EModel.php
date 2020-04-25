<?php declare(strict_types=1);

namespace App\Events\Equipments;

trait EModel
{
    public static string $roomName = 'equipments';

    /**
     * @return string
     */
    public function event(): string
    {
        return 'equipments';
    }
}
