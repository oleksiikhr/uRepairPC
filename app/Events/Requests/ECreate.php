<?php declare(strict_types=1);

namespace App\Events\Requests;

use App\Events\Common\ECreateBroadcast;

class ECreate extends ECreateBroadcast
{
    use EModel;

    /**
     * @return array
     */
    public function rooms(): array
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
     * @return array
     */
    public function join(): array
    {
        return [
            self::$roomName.".{$this->data['id']}",
        ];
    }
}
