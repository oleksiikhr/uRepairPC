<?php

namespace App\Events\EquipmentModels;

trait EModel
{
    public static $roomName = 'equipment_models';

    /**
     * @return string
     */
    public function event(): string
    {
        return 'equipment_models';
    }
}
