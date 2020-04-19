<?php

namespace App\Events\RequestComments;

use App\Events\Common\EDeleteBroadcast;
use Illuminate\Database\Eloquent\Model;

class EDelete extends EDeleteBroadcast
{
    use EModel;

    /**
     * @var int
     */
    private $requestId;

    /**
     * Create a new event instance.
     *
     * @param  int  $requestId
     * @param  Model  $model
     * @return void
     */
    public function __construct(int $requestId, Model $model)
    {
        parent::__construct($model);
        $this->requestId = $requestId;
    }

    /**
     * @return array|string|null
     */
    public function rooms()
    {
        return self::$roomName.".{$this->requestId}.{$this->data['id']}";
    }

    /**
     * @return array|null
     */
    public function params(): ?array
    {
        return [
            'id' => $this->data['id'],
            'request_id' => $this->requestId,
        ];
    }
}
