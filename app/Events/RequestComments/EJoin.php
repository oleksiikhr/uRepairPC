<?php

namespace App\Events\RequestComments;

use App\Events\Common\EJoinBroadcast;

class EJoin extends EJoinBroadcast
{
    use EModel;

    public function __construct(int $requestId, ...$items)
    {
        $rooms = [];

        foreach ($items as $item) {
            $rooms[] = self::$roomName.".{$requestId}.{$item->id}";
        }

        parent::__construct($rooms, false);
    }
}
