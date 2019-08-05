<?php

namespace App\Events\EquipmentFiles;

use App\Events\Common\EUpdateBroadcast;

class EUpdate extends EUpdateBroadcast
{
    use EModel;

    /**
     * @var int
     */
    private $_equipmentId;

    /**
     * Create a new event instance.
     *
     * @param  int  $equipmentId
     * @param  int  $id
     * @param  mixed  $data
     * @return void
     */
    public function __construct(int $equipmentId, int $id, $data)
    {
        parent::__construct($id, $data);
        $this->_equipmentId = $equipmentId;
    }

    /**
     * @return array|string|null
     */
    public function rooms()
    {
        return self::$roomName.".{$this->_equipmentId}.{$this->id}";
    }

    /**
     * @return array|null
     */
    public function params(): ?array
    {
        return [
            'id' => $this->id,
            'equipment_id' => $this->_equipmentId,
        ];
    }
}
