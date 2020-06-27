<?php declare(strict_types=1);

use App\Models\User;
use Illuminate\Database\Seeder;

class UsersTableSeeder extends Seeder
{
    /**
     * Run the database seeds
     *
     * @return void
     */
    public function run(): void
    {
        $user = User::create([
            'first_name' => 'Oliver',
            'last_name' => 'Noah',
            'email' => 'admin@example.com',
            'password' => '$2y$10$YKtkMFVFik8o/PYWOYb9ZekkSpuczZKL5sNxZteqU4quLWEue1f6S', // admin123
        ]);

        $user->assignRolesById(1); // Admin

        if (config('app.env') === 'local') {
            factory(User::class, 70)
                ->create()
                ->each(static function ($user) {
                    $user->assignRolesById(2); // User
                });
        }
    }
}
