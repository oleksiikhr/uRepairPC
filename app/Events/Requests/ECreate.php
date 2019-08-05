<?php

namespace App\Events\Requests;

use App\Events\Common\ECreateBroadcast;

class ECreate extends ECreateBroadcast
{
    use EModel;

    /**
     * @return array|string|null
     */
    public function rooms()
    {
        $rooms = [
            self::$roomName,
            self::$roomName." [user_id.{$this->data['user_id']}]",
        ];

        if (isset($this->data['assign_id'])) {
            $rooms[] = self::$roomName." [assign_id.{$this->data['assign_id']}]";
        }

        return $rooms;
    }

    /**
     * @return array|string
     */
    protected function join()
    {
        return self::$roomName.".{$this->data['id']}";
    }
}
