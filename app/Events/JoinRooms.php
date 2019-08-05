<?php

namespace App\Events;

use App\Events\Common\EJoinBroadcast;

class JoinRooms extends EJoinBroadcast
{
    /**
     * @return string
     */
    public function event(): string
    {
        return 'listeners';
    }
}
