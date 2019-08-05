<?php

namespace App\Events\Equipments;

use App\Events\Common\EJoinBroadcast;
use App\Events\EquipmentFiles\EGlobal as EquipmentFiles;

class EJoin extends EJoinBroadcast
{
    use EModel;

    public function __construct(...$items)
    {
        $rooms = [];

        foreach ($items as $item) {
            $rooms[] = self::$roomName.".{$item->id}";
            array_push($rooms, ...EquipmentFiles::getRooms($item->id));
        }

        parent::__construct($rooms, false);
    }
}
