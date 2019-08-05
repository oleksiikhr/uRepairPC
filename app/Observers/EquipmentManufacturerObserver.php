<?php

namespace App\Observers;

use App\EquipmentManufacturer;
use App\Events\EquipmentManufacturers\EDelete;

class EquipmentManufacturerObserver
{
    /**
     * Handle the equipment manufacturer "deleted" event.
     *
     * @param  \App\EquipmentManufacturer  $equipmentManufacturer
     * @return void
     */
    public function deleted(EquipmentManufacturer $equipmentManufacturer)
    {
        event(new EDelete($equipmentManufacturer));
    }
}
