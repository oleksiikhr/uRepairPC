<?php

namespace App\Events\RequestTypes;

use App\Events\Common\EJoinBroadcast;

class EJoin extends EJoinBroadcast
{
    use EModel;

    public function __construct(...$items)
    {
        $rooms = [];

        foreach ($items as $item) {
            $rooms = self::$roomName.".{$item->id}";
        }

        parent::__construct($rooms, false);
    }
}
