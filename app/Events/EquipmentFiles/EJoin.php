<?php

namespace App\Events\EquipmentFiles;

use App\Events\Common\EJoinBroadcast;

class EJoin extends EJoinBroadcast
{
    use EModel;

    public function __construct(int $equipmentId, ...$items)
    {
        $rooms = [];

        foreach ($items as $item) {
            $rooms[] = self::$roomName.".{$equipmentId}.{$item->id}";
        }

        parent::__construct($rooms, false);
    }
}
