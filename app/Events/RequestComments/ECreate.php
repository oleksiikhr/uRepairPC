<?php declare(strict_types=1);

namespace App\Events\RequestComments;

use App\Events\Common\ECreateBroadcast;

class ECreate extends ECreateBroadcast
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
     * @param  mixed  $data
     * @return void
     */
    public function __construct(int $requestId, $data)
    {
        parent::__construct($data);

        $this->requestId = $requestId;
    }

    /**
     * @return array
     */
    public function rooms(): array
    {
        return [
            self::$roomName.".{$this->requestId}",
        ];
    }

    /**
     * @return array
     */
    public function params(): array
    {
        return [
            'request_id' => $this->requestId,
        ];
    }

    /**
     * @return array
     */
    public function join(): array
    {
        return [
            self::$roomName.".{$this->requestId}.{$this->data['id']}",
        ];
    }
}
