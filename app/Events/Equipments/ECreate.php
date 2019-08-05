<?php

namespace App\Events\Equipments;

use App\Events\Common\ECreateBroadcast;

class ECreate extends ECreateBroadcast
{
    use EModel;

    /**
     * @return array|string|null
     */
    public function rooms()
    {
        return [
            self::$roomName,
            self::$roomName." [user_id.{$this->data['user_id']}]",
        ];
    }

    /**
     * @return array|string
     */
    protected function join()
    {
        return self::$roomName.".{$this->data['id']}";
    }
}
