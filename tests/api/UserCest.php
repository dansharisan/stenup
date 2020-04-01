<?php
use Symfony\Component\HttpFoundation\Response as Response;
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
}
