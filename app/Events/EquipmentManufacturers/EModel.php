<?php declare(strict_types=1);

namespace App\Events\EquipmentManufacturers;

trait EModel
{
    public static string $roomName = 'equipment_manufacturers';

    /**
     * @return string
     */
    public function event(): string
    {
        return 'equipment_manufacturers';
    }
}
