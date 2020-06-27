<?php declare(strict_types=1);

namespace App\Observers;

use App\Models\EquipmentModel;
use App\Realtime\EquipmentModels\EDelete;

class EquipmentModelObserver
{
    /**
     * Handle the equipment model "deleted" event
     *
     * @param  EquipmentModel  $equipmentModel
     * @return void
     */
    public function deleted(EquipmentModel $equipmentModel): void
    {
        EDelete::dispatchAfterResponse($equipmentModel);
    }
}
