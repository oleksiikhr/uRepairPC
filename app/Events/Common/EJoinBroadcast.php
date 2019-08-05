<?php

namespace App\Events\Common;

abstract class EJoinBroadcast extends EBroadcast
{
    /**
     * @var array
     */
    protected $rooms;

    /**
     * @var bool
     */
    protected $sync;

    /**
     * Create a new event instance.
     *
     * @param  array|string  $rooms
     * @param  bool  $sync
     * @return void
     */
    public function __construct($rooms, bool $sync = false)
    {
        $this->rooms = $rooms;
        $this->sync = $sync;
    }

    /**
     * @return array|string|null
     */
    public function rooms()
    {
        return $this->rooms;
    }

    /**
     * @return string
     */
    public function type(): string
    {
        return $this->sync ? self::TYPE_SYNC : self::TYPE_JOIN;
    }

    /**
     * @return array|null
     */
    public function params(): ?array
    {
        return null;
    }

    /**
     * @return mixed
     */
    public function data()
    {
    }
}
