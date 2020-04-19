<?php declare(strict_types=1);

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use Faker\Generator as Faker;

$factory->define(App\User::class, function (Faker $faker) {
    return [
        'first_name' => $faker->firstName,
        'last_name' => $faker->lastName,
        'email' => $faker->unique()->safeEmail,
        'password' => '$2y$10$YKtkMFVFik8o/PYWOYb9ZekkSpuczZKL5sNxZteqU4quLWEue1f6S', // admin123
        'phone' => $faker->phoneNumber,
    ];
});
