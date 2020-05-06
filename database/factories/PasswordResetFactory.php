<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */
use App\Models as Models;
use Faker\Generator as Faker;
use Illuminate\Support\Str;

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

$factory->define(Models\PasswordReset::class, function (Faker $faker) {
    return [
        'email' => $faker->unique()->safeEmail,
        'token' => Str::random(10),
    ];
});
