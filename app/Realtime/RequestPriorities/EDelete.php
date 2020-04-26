<?php declare(strict_types=1);

namespace App\Realtime\RequestPriorities;

use App\Realtime\Common\EDeleteBroadcast;

class EDelete extends EDeleteBroadcast
{
    use EModel;

    /**
     * @return array
     */
    public function rooms(): array
    {
        return [
            self::$roomName.".{$this->data['id']}",
        ];
    }
}
