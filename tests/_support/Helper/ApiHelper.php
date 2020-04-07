<?php
namespace Helper;

use App\Models\User;
use App\Enums\DefaultRoleType;
use Spatie\Permission\Models\Role;
use App\Enums\UserStatus;

// here you can define custom actions
// all public methods declared in helper class will be available in $I

class ApiHelper extends \Codeception\Module
{
    public function generateMemberUser($status = UserStatus::Active)
    {
        $memberUser = factory(User::class)->create([
             'email_verified_at' => now(),
             'status' => $status
        ]);
        $memberRole = Role::where('name', DefaultRoleType::MEMBER)->first();
        $memberUser->assignRole($memberRole);

        return $memberUser;
    }
}
