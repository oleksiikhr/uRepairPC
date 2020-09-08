<?php

declare(strict_types=1);

namespace App\Realtime\Requests;

use App\Realtime\Common\EJoinBroadcast;
use App\Realtime\RequestFiles\EGlobal as RequestFiles;
use App\Realtime\RequestComments\EGlobal as RequestComments;

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
