<?php

use App\RequestPriority;
use Illuminate\Database\Seeder;

class RequestPrioritiesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $items = [
            ['name' => 'Низький', 'color' => '#8e9eb3', 'default' => false, 'value' => 1, 'user_id' => 1],
            ['name' => 'Звичайний', 'color' => '#5cb85c', 'default' => true, 'value' => 5, 'user_id' => 1],
            ['name' => 'Високий', 'color' => '#ea2e49', 'default' => false, 'value' => 10, 'user_id' => 1],
        ];

        foreach ($items as $item) {
            RequestPriority::create($item);
        }
    }
}
