<?php

use App\EquipmentType;
use Illuminate\Database\Seeder;

class EquipmentTypesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
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
