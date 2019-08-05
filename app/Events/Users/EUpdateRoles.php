<?php

namespace App\Events\Users;

use App\Events\Common\EUpdateBroadcast;

class EUpdateRoles extends EUpdateBroadcast
{
    use EModel;

    /**
     * @return array|string|null
     */
    public function rooms()
    {
        return self::$roomName.".{$this->id}.roles";
    }
}
