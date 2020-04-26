<?php declare(strict_types=1);

namespace App\Realtime\EquipmentFiles;

trait EModel
{
    public static string $roomName = 'equipment_files';

    /**
     * @return string
     */
    public function event(): string
    {
        return 'equipment_files';
    }
}
