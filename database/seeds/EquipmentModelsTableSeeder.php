<?php declare(strict_types=1);

use App\EquipmentModel;
use Illuminate\Database\Seeder;

class EquipmentModelsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run(): void
    {
        $arr = [
            ['name' => 'Модель 1', 'type_id' => 1, 'manufacturer_id' => 1, 'user_id' => 1],
        ];

        foreach ($arr as $item) {
            EquipmentModel::create($item);
        }
    }
}
