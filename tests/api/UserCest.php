<?php
use Symfony\Component\HttpFoundation\Response as Response;
use App\Http\Traits\UtilTrait;
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
        $memberUser = $I->generateMemberUser();

        /* Case: Calling the API while not logged in should return unauthorized error */
        $I->sendGET('/api/users');
        $I->seeUnauthorizedRequestError();

        /* Case: By default, member user, which normally don't have VIEW_USERS permission, shouldn't be able to access this API */
        $I->sendPOST('/api/auth/login', [
            'email' => $memberUser->email,
            'password' => 'password'
        ]);
        $I->sendGET('/api/users');
        $I->seeUnauthorizedRequestError();
    }
}
