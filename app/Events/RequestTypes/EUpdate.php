<?php declare(strict_types=1);

namespace App\Events\RequestTypes;

use App\Events\Common\EUpdateBroadcast;

class EUpdate extends EUpdateBroadcast
{
    use EModel;

    /**
     * @return array
     */
    public function rooms(): array
    {
        return [
            self::$roomName.".{$this->id}",
        ];
    }
}
