<?php

namespace App\Observers;

use App\Equipment;
use App\Events\Equipments\EDelete;

class EquipmentObserver
{
    /**
     * Handle the equipment "deleted" event.
     *
     * @param  \App\Equipment  $equipment
     * @return void
     */
    public function deleted(Equipment $equipment)
    {
        event(new EDelete($equipment));
    }
}
