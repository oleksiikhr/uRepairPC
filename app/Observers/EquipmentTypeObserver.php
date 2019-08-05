<?php

namespace App\Observers;

use App\EquipmentType;
use App\Events\EquipmentTypes\EDelete;

class EquipmentTypeObserver
{
    /**
     * Handle the equipment type "deleted" event.
     *
     * @param  \App\EquipmentType  $equipmentType
     * @return void
     */
    public function deleted(EquipmentType $equipmentType)
    {
        event(new EDelete($equipmentType));
    }
}
