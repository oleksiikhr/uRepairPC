<?php declare(strict_types=1);

namespace App\Realtime\Common;

abstract class ECreateBroadcast extends EBroadcast
{
    /**
     * @var mixed
     */
    protected $data;

    /**
     * Create a new event instance
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
        return $this->data;
    }
}
