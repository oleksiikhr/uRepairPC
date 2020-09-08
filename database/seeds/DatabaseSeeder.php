<?php

declare(strict_types=1);

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run(): void
    {
        $this->call(RolesAndPermissionsSeeder::class);
        $this->call(UsersTableSeeder::class);

        // Requests
        $this->call(RequestStatusesTableSeeder::class);
        $this->call(RequestPrioritiesTableSeeder::class);
        $this->call(RequestTypesTableSeeder::class);

        if (config('app.env') === 'local') {
            // Equipments
            $this->call(EquipmentTypesTableSeeder::class);
            $this->call(EquipmentManufacturersTableSeeder::class);
            $this->call(EquipmentModelsTableSeeder::class);
            $this->call(EquipmentsTableSeeder::class);

            // Requests
            $this->call(RequestsTableSeeder::class);
        }
    }
}
