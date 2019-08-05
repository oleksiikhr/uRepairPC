<?php

namespace App\Events\RequestTypes;

use App\Events\Common\ECreateBroadcast;

class ECreate extends ECreateBroadcast
{
    use EModel;

    /**
     * @return array|string|null
     */
    public function rooms()
    {
        return self::$roomName;
    }

    /**
     * @return array|string
     */
    protected function join()
    {
        return self::$roomName.".{$this->data['id']}";
    }
}
