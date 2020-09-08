<?php

declare(strict_types=1);

namespace App\Realtime\EquipmentManufacturers;

use App\Realtime\Common\ECreateBroadcast;

class ECreate extends ECreateBroadcast
{
    use EModel;

    /**
     * @return array
     */
    public function rooms(): array
    {
        return [
            self::$roomName,
        ];
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
