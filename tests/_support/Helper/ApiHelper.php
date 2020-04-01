<?php
namespace Helper;

use App\Models\User;
use App\Enums\DefaultRoleType;
use Spatie\Permission\Models\Role;

// here you can define custom actions
// all public methods declared in helper class will be available in $I

class ApiHelper extends \Codeception\Module
{
    public function generateMemberUser()
    {
        $memberUser = factory(User::class)->create([
             'email_verified_at' => now()
        ]);
        $memberRole = Role::where('name', DefaultRoleType::MEMBER)->first();
        $memberUser->assignRole($memberRole);

        return $memberUser;
    }
}
