<?php

use Illuminate\Database\Seeder;
use App\Enums\DefaultRoleEnum;
use App\Enums\UserStatusEnum;
use App\Models\User;

class AccountSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        factory(User::class)->create([
            'email' => 'dansharisan@gmail.com',
            'email_verified_at' => now(),
            'password' => '$2y$10$kONctkXHfXNakypS40w6S.k/WIitrpFDng3ObG8O9fmiH8yEC1uWu',
            'status' => UserStatusEnum::Active
        ])->each(function ($user) {
            $user->assignRole(DefaultRoleEnum::ADMINISTRATOR);
        });
    }
}
