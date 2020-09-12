<?php

declare(strict_types=1);

namespace Database\Seeders;

use App\Models\EquipmentType;
use Illuminate\Database\Seeder;

class EquipmentTypesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run(): void
    {
        $names = ['Ноутбуки', 'Планшети', 'Монітори'];

        foreach ($names as $name) {
            EquipmentType::create([
                'name' => $name,
                'user_id' => 1,
            ]);
        }
    }
}
