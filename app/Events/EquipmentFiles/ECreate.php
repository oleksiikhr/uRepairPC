<?php

namespace App\Events\EquipmentFiles;

use Illuminate\Support\Collection;
use App\Events\Common\ECreateBroadcast;

class ECreate extends ECreateBroadcast
{
    use EModel;

    /**
     * @var int
     */
    private $equipmentId;

    /**
     * @var int
     */
    private $userIdUpload;

    /**
     * Create a new event instance.
     *
     * @param  int  $equipmentId
     * @param  Collection  $data
     * @param  int  $userIdUpload
     * @return void
     */
    public function __construct(int $equipmentId, Collection $data, int $userIdUpload)
    {
        parent::__construct($data);
        $this->equipmentId = $equipmentId;
        $this->userIdUpload = $userIdUpload;
    }

    /**
     * @return array|string|null
     */
    public function rooms()
    {
        return [
            self::$roomName.".{$this->equipmentId}",
            self::$roomName.".{$this->equipmentId} [user_id.{$this->userIdUpload}]",
        ];
    }

    /**
     * @return array|null
     */
    public function params(): ?array
    {
        return [
            'equipment_id' => $this->equipmentId,
        ];
    }

    /**
     * @return array|string
     */
    protected function join()
    {
        $rooms = [];

        if (is_array($this->data)) {
            foreach ($this->data as $file) {
                $rooms[] = self::$roomName.".{$this->equipmentId}.{$file['id']}";
            }
        } else {
            $rooms[] = self::$roomName.".{$this->equipmentId}.{$this->data['id']}";
        }

        return $rooms;
    }
}
