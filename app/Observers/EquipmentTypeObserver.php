<?php declare(strict_types=1);

namespace App\Observers;

use App\EquipmentType;
use App\Events\EquipmentTypes\EDelete;

class EquipmentTypeObserver
{
    /**
     * Handle the equipment type "deleted" event.
     *
     * @param  EquipmentType  $equipmentType
     * @return void
     */
    public function deleted(EquipmentType $equipmentType): void
    {
        EDelete::dispatchAfterResponse($equipmentType);
    }
}
