<?php

namespace App\Events\Common;

abstract class ECreateBroadcast extends EBroadcast
{
    /**
     * @var mixed
     */
    protected $data;

    /**
     * Create a new event instance.
     *
     * @param  mixed  $data
     * @return void
     */
    public function __construct($data)
    {
        $this->data = $this->transformData($data);
    }

    /**
     * @return string
     */
    public function type(): string
    {
        return self::TYPE_CREATE;
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
        return $this->data;
    }
}
