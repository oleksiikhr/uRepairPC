<?php declare(strict_types=1);

use App\EquipmentManufacturer;
use Illuminate\Database\Seeder;

class EquipmentManufacturersTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run(): void
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
