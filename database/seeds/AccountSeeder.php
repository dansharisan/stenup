<?php

use Illuminate\Database\Seeder;
use App\Enums as Enums;
use App\Models as Models;

class AccountSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        factory(Models\User::class)->create([
            'email' => 'dansharisan@gmail.com',
            'email_verified_at' => now(),
            'password' => '$2y$10$kONctkXHfXNakypS40w6S.k/WIitrpFDng3ObG8O9fmiH8yEC1uWu',
            'status' => Enums\UserStatusEnum::Active
        ])->each(function ($user) {
            $user->assignRole(Enums\DefaultRoleEnum::ADMINISTRATOR);
        });
    }
}
