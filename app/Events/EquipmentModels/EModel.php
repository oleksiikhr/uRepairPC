<?php declare(strict_types=1);

namespace App\Events\EquipmentModels;

trait EModel
{
    public static string $roomName = 'equipment_models';

    /**
     * @return string
     */
    public function event(): string
    {
        return 'equipment_models';
    }
}
