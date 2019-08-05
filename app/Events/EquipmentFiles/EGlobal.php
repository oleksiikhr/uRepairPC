<?php

namespace App\Events\EquipmentFiles;

use App\Enums\Perm;
use App\Events\Common\EJoinBroadcast;

class EGlobal extends EJoinBroadcast
{
    use EModel;

    /**
     * Create a new event instance.
     *
     * @param int $equipmentId
     * @return void
     */
    public function __construct(int $equipmentId)
    {
        parent::__construct(self::getRooms($equipmentId), false);
    }

    /**
     * @param  int  $equipmentId
     * @return array
     */
    public static function getRooms(int $equipmentId): array
    {
        $user = auth()->user();
        $rooms = [];

        if ($user->perm(Perm::EQUIPMENTS_FILES_VIEW_ALL)) {
            $rooms[] = self::$roomName.".{$equipmentId}";
        }

        if ($user->perm(Perm::EQUIPMENTS_FILES_VIEW_OWN)) {
            $rooms[] = self::$roomName.".{$equipmentId} [user_id.{$user->id}]";
        }

        return $rooms;
    }
}
