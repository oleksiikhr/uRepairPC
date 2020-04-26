<?php declare(strict_types=1);

namespace App\Realtime\Requests;

use App\Enums\Perm;
use App\Realtime\Common\EJoinBroadcast;

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
        $user = auth()->user();
        $rooms = [];

        if ($user->perm(Perm::REQUESTS_VIEW_ALL)) {
            $rooms[] = self::$roomName;
        }

        if ($user->perm(Perm::REQUESTS_VIEW_OWN)) {
            $rooms[] = self::$roomName." [user_id.{$user->id}]";
        }

        if ($user->perm(Perm::REQUESTS_VIEW_ASSIGN)) {
            $rooms[] = self::$roomName." [assign_id.{$user->id}]";
        }

        return $rooms;
    }
}
