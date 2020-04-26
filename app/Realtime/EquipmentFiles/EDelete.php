<?php declare(strict_types=1);

namespace App\Realtime\EquipmentFiles;

use App\Realtime\Common\EDeleteBroadcast;
use Illuminate\Database\Eloquent\Model;

class EDelete extends EDeleteBroadcast
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
     * @param  Model  $model
     * @return void
     */
    public function __construct(int $equipmentId, Model $model)
    {
        parent::__construct($model);
        $this->equipmentId = $equipmentId;
    }

    /**
     * @return array
     */
    public function rooms(): array
    {
        return [
            self::$roomName.".{$this->equipmentId}.{$this->data['id']}",
        ];
    }

    /**
     * @return array|null
     */
    public function params(): array
    {
        return [
            'id' => $this->data['id'],
            'equipment_id' => $this->equipmentId,
        ];
    }
}
