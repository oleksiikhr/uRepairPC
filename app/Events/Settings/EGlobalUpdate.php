<?php

namespace App\Events\Settings;

use App\Events\Common\EUpdateBroadcast;

class EGlobalUpdate extends EUpdateBroadcast
{
    /**
     * Create a new event instance.
     *
     * @param  mixed  $data
     * @return void
     */
    public function __construct($data)
    {
        parent::__construct(-1, $data);
    }

    /**
     * @return string
     */
    public function event(): string
    {
        return 'settings.global';
    }

    /**
     * @return array|string|null
     */
    public function rooms()
    {
    }

    /**
     * @return array|null
     */
    public function params(): ?array
    {
        return null;
    }
}
