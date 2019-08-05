<?php

use Illuminate\Database\Seeder;
use App\Request as RequestModel;

class RequestsTableSeeder extends Seeder
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
                'title' => 'Заявка №1',
                'location' => '3-й поверх, 312 каб.',
                'user_id' => 1,
                'assign_id' => null,
                'type_id' => 1,
                'priority_id' => 1,
                'status_id' => 1,
                'equipment_id' => 1,
            ],
        ];

        foreach ($arr as $item) {
            RequestModel::create($item);
        }
    }
}
