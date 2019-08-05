<?php

namespace App\Events\RequestFiles;

use App\Enums\Perm;
use App\Events\Common\EJoinBroadcast;

class EGlobal extends EJoinBroadcast
{
    use EModel;

    /**
     * Create a new event instance.
     *
     * @param int $requestId
     * @return void
     */
    public function __construct(int $requestId)
    {
        parent::__construct(self::getRooms($requestId), false);
    }

    /**
     * @param  int  $requestId
     * @return array
     */
    public static function getRooms(int $requestId): array
    {
        $user = auth()->user();
        $rooms = [];

        if ($user->perm(Perm::REQUESTS_FILES_VIEW_ALL)) {
            $rooms[] = self::$roomName.".{$requestId}";
        }

        if ($user->perm(Perm::REQUESTS_FILES_VIEW_OWN)) {
            $rooms[] = self::$roomName.".{$requestId} [user_id.{$user->id}]";
        }

        return $rooms;
    }
}
