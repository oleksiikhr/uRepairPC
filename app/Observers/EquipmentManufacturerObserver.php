<?php declare(strict_types=1);

namespace App\Observers;

use App\Models\EquipmentManufacturer;
use App\Realtime\EquipmentManufacturers\EDelete;

class EquipmentManufacturerObserver
{
    /**
     * Handle the equipment manufacturer "deleted" event
     *
     * @param  EquipmentManufacturer  $equipmentManufacturer
     * @return void
     */
    public function deleted(EquipmentManufacturer $equipmentManufacturer): void
    {
        EDelete::dispatchAfterResponse($equipmentManufacturer);
    }
}
