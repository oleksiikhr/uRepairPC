<?php

declare(strict_types=1);

namespace App\Realtime\Equipments;

use App\Realtime\Common\EJoinBroadcast;
use App\Realtime\EquipmentFiles\EGlobal as EquipmentFiles;

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
