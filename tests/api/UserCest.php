<?php

use Symfony\Component\HttpFoundation\Response as Response;
use App\Http\Traits as Traits;
use App\Models as Models;
use Spatie\Permission\Models as SpatiePermissionModels;
use App\Enums as Enums;

class UserCest
{
    use Traits\UtilTrait;
    /**
    * Endpoint: GET /api/users
    * Depends on: login
    **/
    public function index(ApiTester $I)
    {
        // Prepare data
        $memberUser1 = $I->generateMemberUser();
        $memberUser2 = $I->generateMemberUser();

        /* Case: Calling the API while not logged in should return unauthorized error */
        $I->sendGET('/api/users');
        $I->seeUnauthorizedRequestError();

        /* Case: By default, member user, which normally don't have VIEW_USERS permission, shouldn't be able to access this API */
        $I->sendPOST('/api/auth/login', [
            'email' => $memberUser1->email,
            'password' => 'password'
        ]);
        $I->sendGET('/api/users');
        $I->seeUnauthorizedRequestError();

        /* Case: When that user is set to have VIEW_ROLES_PERMISSIONS permission, he could get access to this API */
        $memberUser1->roles[0]->givePermissionTo(Enums\PermissionEnum::VIEW_USERS);
        $I->sendGET('/api/users');
        $I->seeResponseIsJson();
        $I->seeResponseCodeIs(Response::HTTP_OK);
        $I->seeResponseContainsJson(
            [
                'users' => [
                                [
                                    'email' => $memberUser1->email
                                ],
                                [
                                    'email' => $memberUser2->email
                                ]
                            ],
            ]
        );
    }


    /**
    * Endpoint: PATCH /api/users/{id}/ban
    * Depends on: login
    **/
    public function ban(ApiTester $I)
    {
        // Prepare data
        $memberUser = $I->generateMemberUser();

        /* Case: Calling the API while not logged in should return unauthorized error */
        $I->sendPATCH('/api/users/' . $memberUser->id . '/ban');
        $I->seeUnauthorizedRequestError();

        /* Case: By default, member user, which normally don't have UPDATE_USERS permission, shouldn't be able to access this API */
        $I->sendPOST('/api/auth/login', [
            'email' => $memberUser->email,
            'password' => 'password'
        ]);
        $I->sendPATCH('/api/users/' . $memberUser->id . '/ban');
        $I->seeUnauthorizedRequestError();

        // When that user is set to have UPDATE_USERS permission, he could get access to this API //
        $memberUser->roles[0]->givePermissionTo(Enums\PermissionEnum::UPDATE_USERS);
        /* Case: Non-existent user ID should return validation error */
        $nonExistentUserId = 999;
        $I->sendPATCH('/api/users/' . $nonExistentUserId . '/ban');
        $I->seeInvalidUserError();

        /* Case: Successfully ban the user */
        $I->sendPATCH('/api/users/' . $memberUser->id . '/ban');
        $I->seeResponseCodeIs(Response::HTTP_NO_CONTENT);
        // Check data in DB
        $updatedUser = Models\User::find($memberUser->id);
        $I->assertEquals($updatedUser->status, Enums\UserStatusEnum::Banned);
        // This user should not be able to login anymore
        $I->sendPOST('/api/auth/login', [
            'email' => $memberUser->email,
            'password' => 'password'
        ]);
        $I->seeWrongCredentialOrInvalidAccountError();
    }

    /**
    * Endpoint: PATCH /api/users/{id}/unban
    * Depends on: login
    **/
    public function unban(ApiTester $I)
    {
        // Prepare data
        $memberUser = $I->generateMemberUser();
        $bannedMemberUser = $I->generateMemberUser(Enums\UserStatusEnum::Banned);

        /* Case: Calling the API while not logged in should return unauthorized error */
        $I->sendPATCH('/api/users/' . $bannedMemberUser->id . '/unban');
        $I->seeUnauthorizedRequestError();

        /* Case: By default, member user, which normally don't have UPDATE_USERS permission, shouldn't be able to access this API */
        $I->sendPOST('/api/auth/login', [
            'email' => $memberUser->email,
            'password' => 'password'
        ]);
        $I->sendPATCH('/api/users/' . $bannedMemberUser->id . '/unban');
        $I->seeUnauthorizedRequestError();

        // When that user is set to have UPDATE_USERS permission, he could get access to this API //
        $memberUser->roles[0]->givePermissionTo(Enums\PermissionEnum::UPDATE_USERS);
        /* Case: Non-existent user ID should return validation error */
        $nonExistentUserId = 999;
        $I->sendPATCH('/api/users/' . $nonExistentUserId . '/unban');
        $I->seeInvalidUserError();

        /* Case: Successfully unban the user */
        $I->sendPATCH('/api/users/' . $bannedMemberUser->id . '/unban');
        $I->seeResponseCodeIs(Response::HTTP_NO_CONTENT);
        // Check data in DB
        $updatedUser = Models\User::find($bannedMemberUser->id);
        $I->assertEquals($updatedUser->status, Enums\UserStatusEnum::Active);
        // This user should now be able to login
        $I->sendPOST('/api/auth/login', [
            'email' => $bannedMemberUser->email,
            'password' => 'password'
        ]);
        $I->seeResponseIsJson();
        $I->seeResponseCodeIs(Response::HTTP_OK);
    }

    /**
    * Endpoint: DELETE /api/users/{id}
    * Depends on: login
    **/
    public function delete(ApiTester $I)
    {
        // Prepare data
        $memberUser = $I->generateMemberUser();

        /* Case: Calling the API while not logged in should return unauthorized error */
        $I->sendDELETE('/api/users/' . $memberUser->id);
        $I->seeUnauthorizedRequestError();

        /* Case: By default, member user, which normally don't have DELETE_USERS permission, shouldn't be able to access this API */
        $I->sendPOST('/api/auth/login', [
            'email' => $memberUser->email,
            'password' => 'password'
        ]);
        $I->sendDELETE('/api/users/' . $memberUser->id);
        $I->seeUnauthorizedRequestError();

        // When that user is set to have DELETE_USERS permission, he could get access to this API //
        $memberUser->roles[0]->givePermissionTo(Enums\PermissionEnum::DELETE_USERS);
        /* Case: Non-existent user ID should return validation error */
        $nonExistentUserId = 999;
        $I->sendDELETE('/api/users/' . $nonExistentUserId);
        $I->seeInvalidUserError();

        /* Case: Successfully delete the user */
        $I->sendDELETE('/api/users/' . $memberUser->id);
        $I->seeResponseCodeIs(Response::HTTP_NO_CONTENT);
        // Check data in DB
        $deletedUser = Models\User::find($memberUser->id);
        $I->assertNull($deletedUser);
        // This user should not be able to login anymore (because the account has already been deleted)
        $I->sendPOST('/api/auth/login', [
            'email' => $memberUser->email,
            'password' => 'password'
        ]);
        $I->seeWrongCredentialOrInvalidAccountError();
    }

    /**
    * Endpoint: POST /api/users/collection:batchDelete
    * Depends on: login
    **/
    public function batchDelete(ApiTester $I)
    {
        // Prepare data
        $memberUser1 = $I->generateMemberUser();
        $memberUser2 = $I->generateMemberUser();

        /* Case: Calling the API while not logged in should return unauthorized error */
        $I->sendPOST('/api/users/collection:batchDelete', [
            'ids' => "$memberUser1->id,$memberUser2->id"
        ]);
        $I->seeUnauthorizedRequestError();

        /* Case: By default, member user, which normally don't have DELETE_USERS permission, shouldn't be able to access this API */
        $I->sendPOST('/api/auth/login', [
            'email' => $memberUser2->email,
            'password' => 'password'
        ]);
        $I->sendPOST('/api/users/collection:batchDelete', [
            'ids' => "$memberUser1->id,$memberUser2->id"
        ]);
        $I->seeUnauthorizedRequestError();

        // When that user is set to have DELETE_USERS permission, he could get access to this API //
        $memberUser1->roles[0]->givePermissionTo(Enums\PermissionEnum::DELETE_USERS);
        /* Case: Empty IDs should return invalid user id string sequence error */
        $I->sendPOST('/api/users/collection:batchDelete', [
            'ids' => ""
        ]);
        $I->seeInvalidUserIDStringSequenceError();

        /* Case: Invalid IDs string sequence should return invalid user id string sequence error */
        $invalidStringSequence = '[,8';
        $I->sendPOST('/api/users/collection:batchDelete', [
            'ids' => $invalidStringSequence
        ]);
        $I->seeInvalidUserIDStringSequenceError();

        /* Case: Successfully delete selected users */
        $I->sendPOST('/api/users/collection:batchDelete', [
            'ids' => "$memberUser1->id,$memberUser2->id"
        ]);
        $I->seeResponseCodeIs(Response::HTTP_NO_CONTENT);
         // Check data in DB
        $deletedUser1 = Models\User::find($memberUser1->id);
        $deletedUser2 = Models\User::find($memberUser2->id);
        $I->assertNull($deletedUser1);
        $I->assertNull($deletedUser2);
        // These two users should not be able to login anymore (because the account has already been deleted)
        $I->sendPOST('/api/auth/login', [
            'email' => $memberUser1->email,
            'password' => 'password'
        ]);
        $I->seeWrongCredentialOrInvalidAccountError();
        $I->sendPOST('/api/auth/login', [
            'email' => $memberUser2->email,
            'password' => 'password'
        ]);
        $I->seeWrongCredentialOrInvalidAccountError();
    }

    /**
    * Endpoint: PATCH /api/users/{id}
    * Depends on: login
    **/
    public function update(ApiTester $I)
    {
        // Prepare data
        $memberUser = $I->generateMemberUser();
        $roleMember = SpatiePermissionModels\Role::where('name', Enums\DefaultRoleEnum::MEMBER)->first();
        $roleAdministrator = SpatiePermissionModels\Role::where('name', Enums\DefaultRoleEnum::ADMINISTRATOR)->first();

        /* Case: Calling the API while not logged in should return unauthorized error */
        $I->sendPATCH('/api/users/' . $memberUser->id, [
            'status' => Enums\UserStatusEnum::Banned,
            'role_ids' => $roleMember->id . ',' . $roleAdministrator->id
        ]);
        $I->seeUnauthorizedRequestError();

        /* Case: By default, member user, which normally don't have UPDATE_USERS permission, shouldn't be able to access this API */
        $I->sendPOST('/api/auth/login', [
            'email' => $memberUser->email,
            'password' => 'password'
        ]);
        $I->sendPATCH('/api/users/' . $memberUser->id, [
            'status' => Enums\UserStatusEnum::Banned,
            'role_ids' => $roleMember->id . ',' . $roleAdministrator->id
        ]);
        $I->seeUnauthorizedRequestError();

        // When that user is set to have UPDATE_USERS permission, he could get access to this API //
        $memberUser->roles[0]->givePermissionTo(Enums\PermissionEnum::UPDATE_USERS);
        /* Case: Empty role IDs should return invalid or no role selected error */
        $I->sendPATCH('/api/users/' . $memberUser->id, [
            'status' => Enums\UserStatusEnum::Banned,
            'role_ids' => ''
        ]);
        $I->seeInvalidOrNoRoleSelectedError();

        /* Case: Invalid role IDs string sequence should return invalid or no role selected error */
        $invalidStringSequence = '[,8';
        $I->sendPATCH('/api/users/' . $memberUser->id, [
            'status' => Enums\UserStatusEnum::Banned,
            'role_ids' => $invalidStringSequence
        ]);
        $I->seeInvalidOrNoRoleSelectedError();

        /* Case: Non-existent role should return invalid or no role selected error */
        $nonExistentRoleIdArr = '9999,0';
        $I->sendPATCH('/api/users/' . $memberUser->id, [
            'status' => Enums\UserStatusEnum::Banned,
            'role_ids' => $nonExistentRoleIdArr
        ]);
        $I->seeServerError();

        /* Case: Successfully update the user */
        $emailVerifiedAt = '2020-01-13 14:05:49';
        $I->sendPATCH('/api/users/' . $memberUser->id, [
            'status' => Enums\UserStatusEnum::Banned,
            'role_ids' => $roleMember->id . ',' . $roleAdministrator->id,
            'email_verified_at' => $emailVerifiedAt
        ]);
        $I->seeResponseIsJson();
        $I->seeResponseCodeIs(Response::HTTP_OK);
        $I->seeResponseContainsJson([
            'user' => [
                'status' => Enums\UserStatusEnum::Banned,
                'roles' => [
                              [
                                  'name' => Enums\DefaultRoleEnum::ADMINISTRATOR
                              ],
                              [
                                  'name' => Enums\DefaultRoleEnum::MEMBER
                              ]
                          ],
                      ]
        ]);
        // Check data in DB
        $updatedUser = Models\User::find($memberUser->id);
        $I->assertEquals($updatedUser->status, Enums\UserStatusEnum::Banned);
        $I->assertEquals($updatedUser->email_verified_at, $emailVerifiedAt);
        $I->assertTrue($updatedUser->hasRole(Enums\DefaultRoleEnum::ADMINISTRATOR));
        $I->assertTrue($updatedUser->hasRole(Enums\DefaultRoleEnum::MEMBER));
    }

    /**
    * Endpoint: POST /api/users
    * Depends on: login
    **/
    public function store(ApiTester $I)
    {
        // Prepare data
        $memberUser = $I->generateMemberUser();
        $roleMember = SpatiePermissionModels\Role::where('name', Enums\DefaultRoleEnum::MEMBER)->first();
        $roleAdministrator = SpatiePermissionModels\Role::where('name', Enums\DefaultRoleEnum::ADMINISTRATOR)->first();
        $validEmail = 'my_valid_email@test.com';

        /* Case: Calling the API while not logged in should return unauthorized error */
        $I->sendPOST('/api/users', [
            'email' => 'myemail@test.com',
            'password' => '123456',
            'status' => Enums\UserStatusEnum::Active,
            'role_ids' => $roleMember->id . ',' . $roleAdministrator->id
        ]);
        $I->seeUnauthorizedRequestError();

        /* Case: By default, member user, which normally don't have CREATE_USERS permission, shouldn't be able to access this API */
        $I->sendPOST('/api/auth/login', [
            'email' => $memberUser->email,
            'password' => 'password'
        ]);
        $I->sendPOST('/api/users', [
            'email' => 'myemail@test.com',
            'password' => '123456',
            'status' => Enums\UserStatusEnum::Active,
            'role_ids' => $roleMember->id . ',' . $roleAdministrator->id
        ]);
        $I->seeUnauthorizedRequestError();

        // When that user is set to have CREATE_USERS permission, he could get access to this API //
        $memberUser->roles[0]->givePermissionTo(Enums\PermissionEnum::CREATE_USERS);
        /* Case: Empty email should return validation error */
        $I->sendPOST('/api/users', [
            'email' => '',
            'password' => '123456',
            'status' => Enums\UserStatusEnum::Active,
            'role_ids' => $roleMember->id . ',' . $roleAdministrator->id
        ]);
        $I->seeValidationError();

        /* Case: Invalid email should return validation error */
        $I->sendPOST('/api/users', [
            'email' => 'invalid_email',
            'password' => '123456',
            'status' => Enums\UserStatusEnum::Active,
            'role_ids' => $roleMember->id . ',' . $roleAdministrator->id
        ]);
        $I->seeValidationError();

        /* Case: Existing email should return validation error */
        $I->sendPOST('/api/users', [
            'email' => $memberUser->email,
            'password' => '123456',
            'status' => Enums\UserStatusEnum::Active,
            'role_ids' => $roleMember->id . ',' . $roleAdministrator->id
        ]);
        $I->seeValidationError();

        /* Case: Empty password should return validation error */
        $I->sendPOST('/api/users', [
            'email' => $validEmail,
            'password' => '',
            'status' => Enums\UserStatusEnum::Active,
            'role_ids' => $roleMember->id . ',' . $roleAdministrator->id
        ]);
        $I->seeValidationError();

        /* Case: Empty role id string sequence should return validation error */
        $I->sendPOST('/api/users', [
            'email' => $validEmail,
            'password' => '123456',
            'status' => Enums\UserStatusEnum::Active,
            'role_ids' => ''
        ]);
        $I->seeValidationError();

        /* Case: Invalid role IDs string sequence should return invalid or no role selected error */
        $invalidStringSequence = '[,8';
        $I->sendPOST('/api/users', [
            'email' => $validEmail,
            'password' => '123456',
            'status' => Enums\UserStatusEnum::Active,
            'role_ids' => $invalidStringSequence
        ]);
        $I->seeInvalidOrNoRoleSelectedError();

        /* Case: Non-existent role should return invalid or no role selected error */
        $nonExistentRoleIdArr = '9999,0';
        $I->sendPOST('/api/users', [
            'email' => $validEmail,
            'password' => '123456',
            'status' => Enums\UserStatusEnum::Active,
            'role_ids' => $nonExistentRoleIdArr
        ]);
        $I->seeServerError();

        /* Case: Successfully add the user */
        $I->sendPOST('/api/users', [
            'email' => $validEmail,
            'password' => '123456',
            'status' => Enums\UserStatusEnum::Active,
            'role_ids' => $roleMember->id . ',' . $roleAdministrator->id
        ]);
        $I->seeResponseIsJson();
        $I->seeResponseCodeIs(Response::HTTP_OK);
        $I->seeResponseContainsJson([
            'user' => [
                'email' => $validEmail,
                'roles' => [
                              [
                                  'name' => Enums\DefaultRoleEnum::ADMINISTRATOR
                              ],
                              [
                                  'name' => Enums\DefaultRoleEnum::MEMBER
                              ]
                          ],
                      ]
        ]);
    }

    /**
    * Endpoint: GET /api/users/registered_user_stats
    * Depends on: login
    **/
    public function registeredUserStats(ApiTester $I)
    {
        // Prepare data
        $memberUser = $I->generateMemberUser();

        /* Case: Calling the API while not logged in should return unauthorized error */
        $I->sendGET('/api/users/registered_user_stats');
        $I->seeUnauthorizedRequestError();

        /* Case: By default, member user, which normally don't have VIEW_DASHBOARD permission, shouldn't be able to access this API */
        $I->sendPOST('/api/auth/login', [
            'email' => $memberUser->email,
            'password' => 'password'
        ]);
        $I->sendGET('/api/users/registered_user_stats');
        $I->seeUnauthorizedRequestError();

        /* Case: When that user is set to have VIEW_DASHBOARD permission, he could get access to this API */
        $memberUser->roles[0]->givePermissionTo(Enums\PermissionEnum::VIEW_DASHBOARD);
        $I->sendGET('/api/users/registered_user_stats');
        $I->seeResponseIsJson();
        $I->seeResponseCodeIs(Response::HTTP_OK);
        $I->seeResponseContainsJson(
            [
                'user_stats' => [
                    // 'total' => ,
                    'last_7_day_stats' => []
                ],
            ]
        );
    }
}
