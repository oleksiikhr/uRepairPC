<?php declare(strict_types=1);

namespace App\Events\RequestFiles;

use App\Events\Common\EUpdateBroadcast;

class EUpdate extends EUpdateBroadcast
{
    use EModel;

    /**
     * @var int
     */
    private int $requestId;

    /**
     * Create a new event instance.
     *
     * @param  int  $requestId
     * @param  int  $id
     * @param  mixed  $data
     * @return void
     */
    public function __construct(int $requestId, int $id, $data)
    {
        parent::__construct($id, $data);

        $this->requestId = $requestId;
    }

    /**
     * @return array
     */
    public function rooms(): array
    {
        return [
            self::$roomName.".{$this->requestId}.{$this->id}",
        ];
    }

    /**
     * @return array
     */
    public function params(): array
    {
        return [
            'id' => $this->id,
            'request_id' => $this->requestId,
        ];
    }
}
