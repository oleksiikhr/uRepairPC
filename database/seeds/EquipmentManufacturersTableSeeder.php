<?php

use App\EquipmentManufacturer;
use Illuminate\Database\Seeder;

class EquipmentManufacturersTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $names = ['Acer', 'Apple', 'Asus', 'Dell', 'HP', 'Lenovo', 'Samsung'];

        foreach ($names as $name) {
            EquipmentManufacturer::create([
                'name' => $name,
                'user_id' => 1,
            ]);
        }
    }
}
