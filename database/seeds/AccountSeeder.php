<?php

use Illuminate\Database\Seeder;
use App\Enums\DefaultRoleType;
use App\Enums\UserStatus;
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
            'password' => '$2y$10$kONctkXHfXNakypS40w6S.k/WIitrpFDng3ObG8O9fmiH8yEC1uWu',
            'status' => UserStatus::Active
        ])->each(function ($user) {
            $user->assignRole(DefaultRoleType::Administrator);
        });
    }
}
