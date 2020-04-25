<?php declare(strict_types=1);

namespace App\Events\Users;

use App\Events\Common\EUpdateBroadcast;

class EUpdateRoles extends EUpdateBroadcast
{
    use EModel;

    /**
     * @return array
     */
    public function rooms(): array
    {
        return [
            self::$roomName.".{$this->id}.roles",
        ];
    }
}
