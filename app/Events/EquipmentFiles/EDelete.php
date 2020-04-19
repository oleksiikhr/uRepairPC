<?php

namespace App\Events\EquipmentFiles;

use App\Events\Common\EDeleteBroadcast;
use Illuminate\Database\Eloquent\Model;

class EDelete extends EDeleteBroadcast
{
    use EModel;

    /**
     * @var int
     */
    private $equipmentId;

    /**
     * Create a new event instance.
     *
     * @param  int  $equipmentId
     * @param  Model  $model
     * @return void
     */
    public function __construct(int $equipmentId, Model $model)
    {
        parent::__construct($model);
        $this->equipmentId = $equipmentId;
    }

    /**
     * @return array|string|null
     */
    public function rooms()
    {
        return self::$roomName.".{$this->equipmentId}.{$this->data['id']}";
    }

    /**
     * @return array|null
     */
    public function params(): ?array
    {
        return [
            'id' => $this->data['id'],
            'equipment_id' => $this->equipmentId,
        ];
    }
}
