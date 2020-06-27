<?php declare(strict_types=1);

namespace App\Realtime\RequestComments;

use App\Realtime\Common\EDeleteBroadcast;
use Illuminate\Database\Eloquent\Model;

class EDelete extends EDeleteBroadcast
{
    use EModel;

    /**
     * @var int
     */
    private int $requestId;

    /**
     * Create a new event instance
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
     * @return array
     */
    public function rooms(): array
    {
        return [
            self::$roomName.".{$this->requestId}.{$this->data['id']}",
        ];
    }

    /**
     * @return array
     */
    public function params(): array
    {
        return [
            'id' => $this->data['id'],
            'request_id' => $this->requestId,
        ];
    }
}
