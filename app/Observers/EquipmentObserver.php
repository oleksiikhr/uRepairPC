<?php

declare(strict_types=1);

namespace App\Observers;

use App\Models\Equipment;
use App\Realtime\Equipments\EDelete;

class EquipmentObserver
{
    /**
     * Handle the equipment "deleted" event.
     *
     * @param  Equipment  $equipment
     * @return void
     */
    public function deleted(Equipment $equipment): void
    {
        EDelete::dispatch($equipment);
    }
}
