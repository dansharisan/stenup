<?php
use Symfony\Component\HttpFoundation\Response as Response;
use App\Models\User;

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

        // Case: Unverified account should not be able to login
        $unverifiedUser = factory(User::class)->create();
        $I->sendPOST('/api/auth/login', [
            'email' => $unverifiedUser->email,
            'password' => 'password'
        ]);
        $this->seeUnverifiedEmailError($I);

        // Case: Wrong credential should return unauthorized response
        $verifiedUser = factory(User::class)->create([
            'email_verified_at' => now()
        ]);
        $I->sendPOST('/api/auth/login', [
            'email' => $verifiedUser->email,
            'password' => 'wrongpassword'
        ]);
        $this->seeWrongCredentialOrInvalidAccountError($I);
    }

    public function loginSuccess(ApiTester $I)
    {
        $verifiedUser = factory(User::class)->create([
            'email_verified_at' => now(),
        ]);

        $I->sendPOST('/api/auth/login', [
            'email' => $verifiedUser->email,
            'password' => 'password'
        ]);

        $I->seeResponseIsJson();
        $I->seeResponseCodeIs(Response::HTTP_OK);
    }

    private function seeUnverifiedEmailError(ApiTester $I)
    {
        $I->seeResponseIsJson();
        $I->seeResponseContainsJson(['error' => ['code' => 'AUTH0011']]);
        $I->seeResponseCodeIs(Response::HTTP_UNAUTHORIZED);
    }

    private function seeWrongCredentialOrInvalidAccountError(ApiTester $I)
    {
        $I->seeResponseIsJson();
        $I->seeResponseContainsJson(['error' => ['code' => 'AUTH0001']]);
        $I->seeResponseCodeIs(Response::HTTP_UNAUTHORIZED);
    }

    private function seeValidationError(ApiTester $I)
    {
        $I->seeResponseIsJson();
        $I->seeResponseContainsJson(['error' => ['code' => 'GENR0002']]);
        $I->seeResponseCodeIs(Response::HTTP_UNPROCESSABLE_ENTITY);
    }
}
