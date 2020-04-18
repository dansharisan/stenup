<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */
use App\Models as Models;
use Faker\Generator as Faker;
use Illuminate\Support\Str;
use App\Enums as Enums;

/*
|--------------------------------------------------------------------------
| Model Factories
|--------------------------------------------------------------------------
|
| This directory should contain each of the model factory definitions for
| your application. Factories provide a convenient way to generate new
| model instances for testing / seeding your application's database.
|
*/

$factory->define(Models\User::class, function (Faker $faker) {
    return [
        'email' => $faker->unique()->safeEmail,
        //'email_verified_at' => now(),
        'status' => Enums\UserStatusEnum::Active,
        'password' => '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', // password
        'remember_token' => Str::random(10),
    ];
});
