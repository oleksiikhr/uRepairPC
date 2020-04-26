<?php declare(strict_types=1);

namespace App\Realtime\EquipmentFiles;

use App\Realtime\Common\EUpdateBroadcast;

class EUpdate extends EUpdateBroadcast
{
    use EModel;

    /**
     * @var int
     */
    private int $equipmentId;

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

        $this->equipmentId = $equipmentId;
    }

    /**
     * @return array
     */
    public function rooms(): array
    {
        return [
            self::$roomName.".{$this->equipmentId}.{$this->id}",
        ];
    }

    /**
     * @return array
     */
    public function params(): array
    {
        return [
            'id' => $this->id,
            'equipment_id' => $this->equipmentId,
        ];
    }
}
