<?php

namespace App\Events\RequestFiles;

use Illuminate\Support\Collection;
use App\Events\Common\ECreateBroadcast;

class ECreate extends ECreateBroadcast
{
    use EModel;

    /**
     * @var int
     */
    private $_requestId;

    /**
     * @var int
     */
    private $_userIdUpload;

    /**
     * Create a new event instance.
     *
     * @param  int  $requestId
     * @param  Collection  $data
     * @param  int  $userIdUpload
     * @return void
     */
    public function __construct(int $requestId, Collection $data, int $userIdUpload)
    {
        parent::__construct($data);
        $this->_requestId = $requestId;
        $this->_userIdUpload = $userIdUpload;
    }

    /**
     * @return array|string|null
     */
    public function rooms()
    {
        return [
            self::$roomName.".{$this->_requestId}",
            self::$roomName.".{$this->_requestId} [user_id.{$this->_userIdUpload}]",
        ];
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
     * @return string|array
     */
    protected function join()
    {
        $rooms = [];

        if (is_array($this->data)) {
            foreach ($this->data as $file) {
                $rooms[] = self::$roomName.".{$this->_requestId}.{$file['id']}";
            }
        } else {
            $rooms[] = self::$roomName.".{$this->_requestId}.{$this->data['id']}";
        }

        return $rooms;
    }
}
