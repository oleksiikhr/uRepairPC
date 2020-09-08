<?php

declare(strict_types=1);

namespace App\Realtime\RequestTypes;

use App\Realtime\Common\EJoinBroadcast;

class EJoin extends EJoinBroadcast
{
    use EModel;

    public function __construct(...$items)
    {
        $rooms = [];

        foreach ($items as $item) {
            $rooms[] = self::$roomName.".{$item->id}";
        }

        parent::__construct($rooms, false);
    }
}
