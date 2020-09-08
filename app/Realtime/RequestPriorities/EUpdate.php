<?php

declare(strict_types=1);

namespace App\Realtime\RequestPriorities;

use App\Realtime\Common\EUpdateBroadcast;

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
