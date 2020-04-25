<?php declare(strict_types=1);

namespace App\Events\EquipmentTypes;

trait EModel
{
    public static string $roomName = 'equipment_types';

    /**
     * @return string
     */
    public function event(): string
    {
        return 'equipment_types';
    }
}
