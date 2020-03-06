<?php
use Symfony\Component\HttpFoundation\Response as Response;

class AuthenticationCest
{
    public function loginFailure(ApiTester $I)
    {
        // Send the request
        $I->sendPOST('/auth/login', [
            'email' => 'fake_email@something.com',
            'password' => 'anything'
        ]);

        $I->seeResponseCodeIs(Response::HTTP_UNAUTHORIZED);
    }
}
