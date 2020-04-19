<?php declare(strict_types=1);

namespace App\Observers;

use App\EquipmentModel;
use App\Events\EquipmentModels\EDelete;

class EquipmentModelObserver
{
    /**
     * Handle the equipment model "deleted" event.
     *
     * @param  EquipmentModel  $equipmentModel
     * @return void
     */
    public function deleted(EquipmentModel $equipmentModel): void
    {
        event(new EDelete($equipmentModel));
    }
}
