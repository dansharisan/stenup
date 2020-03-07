<?php
use Symfony\Component\HttpFoundation\Response as Response;

class AuthenticationCest
{
    public function loginFailure(ApiTester $I)
    {
        // Case: Empty email should return validation error
        $I->sendPOST('/api/auth/login', [
            'email' => '',
            'password' => 'anything'
        ]);
        $this->seeValidationError($I);

        // Case: Email not valid should return validation error
        $I->sendPOST('/api/auth/login', [
            'email' => 'invalid_email',
            'password' => 'anything'
        ]);
        $this->seeValidationError($I);

        // Case: Empty password should return validation error
        $I->sendPOST('/api/auth/login', [
            'email' => 'some_email@something.com',
            'password' => ''
        ]);
        $this->seeValidationError($I);
    }

    private function seeValidationError(ApiTester $I)
    {
        $I->seeResponseIsJson();
        $I->seeResponseContainsJson(['error' => ['code' => 'GENR0002']]);
        $I->seeResponseCodeIs(Response::HTTP_UNPROCESSABLE_ENTITY);
    }
}
