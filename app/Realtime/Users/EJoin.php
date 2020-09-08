<?php

declare(strict_types=1);

namespace App\Realtime\Users;

use App\Enums\Perm;
use App\Realtime\Common\EJoinBroadcast;

class EJoin extends EJoinBroadcast
{
    use EModel;

    public function __construct(...$items)
    {
        $canViewAllRoles = perm(Perm::ROLES_VIEW_ALL);
        $rooms = [];

        foreach ($items as $item) {
            $rooms[] = self::$roomName.".{$item->id}";
            if ($canViewAllRoles) {
                $rooms[] = self::$roomName.".{$item->id}.roles";
            }
        }

        parent::__construct($rooms, false);
    }
}
