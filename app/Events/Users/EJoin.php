<?php

namespace App\Events\Users;

use App\Enums\Perm;
use App\Events\Common\EJoinBroadcast;

class EJoin extends EJoinBroadcast
{
    use EModel;

    public function __construct(...$items)
    {
        $canViewAllRoles = auth()->user()->perm(Perm::ROLES_VIEW_ALL);
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
