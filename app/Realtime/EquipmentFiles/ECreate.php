<?php declare(strict_types=1);

namespace App\Realtime\EquipmentFiles;

use Illuminate\Support\Collection;
use App\Realtime\Common\ECreateBroadcast;

class ECreate extends ECreateBroadcast
{
    use EModel;

    /**
     * @var int
     */
    private int $equipmentId;

    /**
     * @var int
     */
    private int $userIdUpload;

    /**
     * Create a new event instance
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
     * @return array
     */
    public function rooms(): array
    {
        return [
            self::$roomName.".{$this->equipmentId}",
            self::$roomName.".{$this->equipmentId} [user_id.{$this->userIdUpload}]",
        ];
    }

    /**
     * @return array
     */
    public function params(): array
    {
        return [
            'equipment_id' => $this->equipmentId,
        ];
    }

    /**
     * @return array
     */
    public function join(): array
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
