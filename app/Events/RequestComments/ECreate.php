<?php

namespace App\Events\RequestComments;

use App\Events\Common\ECreateBroadcast;

class ECreate extends ECreateBroadcast
{
    use EModel;

    /**
     * @var int
     */
    private $_requestId;

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
        $this->_requestId = $requestId;
    }

    /**
     * @return array|string|null
     */
    public function rooms()
    {
        return self::$roomName.".{$this->_requestId}";
    }

    /**
     * @return array|null
     */
    public function params(): ?array
    {
        return [
            'request_id' => $this->_requestId,
        ];
    }

    /**
     * @return array|string
     */
    protected function join()
    {
        return self::$roomName.".{$this->_requestId}.{$this->data['id']}";
    }
}
