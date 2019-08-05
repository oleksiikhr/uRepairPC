<?php

namespace App\Events\Common;

abstract class EUpdateBroadcast extends EBroadcast
{
    /**
     * @var int
     */
    protected $id;

    /**
     * @var mixed
     */
    protected $data;

    /**
     * Create a new event instance.
     *
     * @param  int  $id
     * @param  mixed  $data
     * @return void
     */
    public function __construct(int $id, $data)
    {
        $this->id = $id;
        $this->data = $this->transformData($data);
    }

    /**
     * @return string
     */
    public function type(): string
    {
        return self::TYPE_UPDATE;
    }

    /**
     * @return array|null
     */
    public function params(): ?array
    {
        return [
            'id' => $this->id,
        ];
    }

    /**
     * @return mixed
     */
    public function data()
    {
        return $this->data;
    }
}
