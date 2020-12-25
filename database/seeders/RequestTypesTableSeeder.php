<?php

declare(strict_types=1);

namespace Database\Seeders;

use App\Models\RequestType;
use Illuminate\Database\Seeder;

class RequestTypesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run(): void
    {
        $items = [
            [
                'name' => 'Інцидент',
                'description' => 'Заявки, пов\'язані з поломкою або несправністю',
                'default' => true,
                'user_id' => 1,
            ],
            [
                'name' => 'Обслуговування',
                'description' => 'Заявки, пов\'язані з плановим обслуговуванням',
                'default' => false,
                'user_id' => 1,
            ],
        ];

        foreach ($items as $item) {
            RequestType::create($item);
        }
    }
}
