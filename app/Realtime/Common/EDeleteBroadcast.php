<?php declare(strict_types=1);

namespace App\Realtime\Common;

use Illuminate\Database\Eloquent\Model;

abstract class EDeleteBroadcast extends EBroadcast
{
    /**
     * @var mixed
     */
    protected $data;

    /**
     * Create a new event instance.
     *
     * @param  Model  $model
     * @return void
     */
    public function __construct($model)
    {
        $this->data = $this->transformData($model);
    }

    /**
     * @return string
     */
    public function type(): string
    {
        return self::TYPE_DELETE;
    }

    /**
     * @return array
     */
    public function params(): array
    {
        return [
            'id' => $this->data['id'],
        ];
    }

    /**
     * @return mixed
     */
    public function data()
    {
    }
}
