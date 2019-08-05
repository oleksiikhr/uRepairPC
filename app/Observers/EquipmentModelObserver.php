<?php

namespace App\Observers;

use App\EquipmentModel;
use App\Events\EquipmentModels\EDelete;

class EquipmentModelObserver
{
    /**
     * Handle the equipment model "deleted" event.
     *
     * @param  \App\EquipmentModel  $equipmentModel
     * @return void
     */
    public function deleted(EquipmentModel $equipmentModel)
    {
        event(new EDelete($equipmentModel));
    }
}
