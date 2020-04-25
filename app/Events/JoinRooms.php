<?php declare(strict_types=1);

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
