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
    private $requestId;

    /**
     * @var int
     */
    private $userIdUpload;

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
        $this->requestId = $requestId;
        $this->userIdUpload = $userIdUpload;
    }

    /**
     * @return array|string|null
     */
    public function rooms()
    {
        return [
            self::$roomName.".{$this->requestId}",
            self::$roomName.".{$this->requestId} [user_id.{$this->userIdUpload}]",
        ];
    }

    /**
     * @return array|null
     */
    public function params(): ?array
    {
        return [
            'request_id' => $this->requestId,
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
                $rooms[] = self::$roomName.".{$this->requestId}.{$file['id']}";
            }
        } else {
            $rooms[] = self::$roomName.".{$this->requestId}.{$this->data['id']}";
        }

        return $rooms;
    }
}
