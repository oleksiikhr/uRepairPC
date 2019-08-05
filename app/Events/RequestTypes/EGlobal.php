<?php

namespace App\Events\RequestTypes;

use App\Enums\Perm;
use App\Events\Common\EJoinBroadcast;

class EGlobal extends EJoinBroadcast
{
    use EModel;

    public function __construct()
    {
        parent::__construct(self::getRooms(), false);
    }

    /**
     * @return array
     */
    public static function getRooms(): array
    {
        if (auth()->user()->perm(Perm::REQUESTS_CONFIG_VIEW_ALL)) {
            return [self::$roomName];
        }

        return [];
    }
}
