<?php declare(strict_types=1);

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
     * @return array
     */
    public function rooms(): array
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
     * @return array
     */
    public function params(): array
    {
        return [];
    }

    /**
     * @return mixed
     */
    public function data()
    {
    }
}
