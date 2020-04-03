<?php
use Symfony\Component\HttpFoundation\Response as Response;
use App\Enums\UserStatus;
use App\Http\Traits\UtilTrait;
use App\Enums\PermissionType;
use App\Models\User;

class UserCest
{
    use UtilTrait;
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
        $memberUser1->roles[0]->givePermissionTo(PermissionType::VIEW_USERS);
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
        $memberUser->roles[0]->givePermissionTo(PermissionType::UPDATE_USERS);
        /* Case: Non-existent user ID should return validation error */
        $nonExistentUserId = 999;
        $I->sendPATCH('/api/users/' . $nonExistentUserId . '/ban');
        $I->seeInvalidUserError();

        /* Case: Successfully ban the user */
        $I->sendPATCH('/api/users/' . $memberUser->id . '/ban');
        $I->seeResponseCodeIs(Response::HTTP_NO_CONTENT);
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
        $bannedMemberUser = $I->generateMemberUser(UserStatus::Banned);

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
        $memberUser->roles[0]->givePermissionTo(PermissionType::UPDATE_USERS);
        /* Case: Non-existent user ID should return validation error */
        $nonExistentUserId = 999;
        $I->sendPATCH('/api/users/' . $nonExistentUserId . '/unban');
        $I->seeInvalidUserError();

        /* Case: Successfully unban the user */
        $I->sendPATCH('/api/users/' . $bannedMemberUser->id . '/unban');
        $I->seeResponseCodeIs(Response::HTTP_NO_CONTENT);
        // This user should now be able to login
        $I->sendPOST('/api/auth/login', [
            'email' => $bannedMemberUser->email,
            'password' => 'password'
        ]);
        $I->seeResponseIsJson();
        $I->seeResponseCodeIs(Response::HTTP_OK);
    }
}
