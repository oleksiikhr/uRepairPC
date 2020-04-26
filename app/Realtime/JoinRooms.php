<?php declare(strict_types=1);

namespace App\Realtime;

use App\Realtime\Common\EJoinBroadcast;

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
