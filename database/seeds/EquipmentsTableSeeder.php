<?php

use App\Equipment;
use Illuminate\Database\Seeder;

class EquipmentsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $arr = [
            [
                'serial_number' => '11111',
                'user_id' => 1,
                'inventory_number' => '22222',
                'manufacturer_id' => 1,
                'type_id' => 1,
                'model_id' => 1,
            ],
        ];

        foreach ($arr as $item) {
            Equipment::create($item);
        }
    }
}
