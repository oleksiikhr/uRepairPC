<?php

declare(strict_types=1);

namespace App\Realtime\EquipmentFiles;

use App\Realtime\Common\EJoinBroadcast;

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
