<?php

namespace App\Events\Roles;

use App\Events\Common\EUpdateBroadcast;

class EUpdate extends EUpdateBroadcast
{
    use EModel;

    /**
     * @return array|string|null
     */
    public function rooms()
    {
        return self::$roomName.".{$this->id}";
    }
}
