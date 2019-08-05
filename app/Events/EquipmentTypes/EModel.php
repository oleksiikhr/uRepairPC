<?php

namespace App\Events\EquipmentTypes;

trait EModel
{
    public static $roomName = 'equipment_types';

    /**
     * @return string
     */
    public function event(): string
    {
        return 'equipment_types';
    }
}
