<?php declare(strict_types=1);

namespace App\Events\EquipmentFiles;

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
