<?php

use App\RequestStatus;
use Illuminate\Database\Seeder;

class RequestStatusesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $items = [
            ['name' => 'Відкрита', 'color' => '#ff8522', 'default' => true, 'user_id' => 1],
            ['name' => 'Відкладена', 'color' => '#ffc926', 'default' => false, 'user_id' => 1],
            ['name' => 'Вирішена', 'color' => '#5cb85c', 'default' => false, 'user_id' => 1],
            ['name' => 'Закрита', 'color' => '#8e9eb3', 'default' => false, 'user_id' => 1],
        ];

        foreach ($items as $item) {
            RequestStatus::create($item);
        }
    }
}
