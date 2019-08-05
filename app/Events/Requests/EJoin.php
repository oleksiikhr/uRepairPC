<?php

namespace App\Events\Requests;

use App\Events\Common\EJoinBroadcast;
use App\Events\RequestFiles\EGlobal as RequestFiles;
use App\Events\RequestComments\EGlobal as RequestComments;

class EJoin extends EJoinBroadcast
{
    use EModel;

    public function __construct(...$items)
    {
        $rooms = [];

        foreach ($items as $item) {
            $rooms[] = self::$roomName.".{$item->id}";
            array_push($rooms, ...RequestFiles::getRooms($item->id));
            array_push($rooms, ...RequestComments::getRooms($item->id));
        }

        parent::__construct($rooms, false);
    }
}
