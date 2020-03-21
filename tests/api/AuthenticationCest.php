<?php
use Symfony\Component\HttpFoundation\Response as Response;
use App\Models\User;

class AuthenticationCest
{
    public function login(ApiTester $I)
    {
        /* Case: Empty email should return validation error */
        $I->sendPOST('/api/auth/login', [
            'email' => '',
            'password' => 'anything'
        ]);
        $this->seeValidationError($I);

        /* Case: Email not valid should return validation error */
        $I->sendPOST('/api/auth/login', [
            'email' => 'invalid_email',
            'password' => 'anything'
        ]);
        $this->seeValidationError($I);

        /* Case: Empty password should return validation error */
        $I->sendPOST('/api/auth/login', [
            'email' => 'some_email@something.com',
            'password' => ''
        ]);
        $this->seeValidationError($I);

        /* Case: Unverified account should not be able to login */
        $unverifiedUser = factory(User::class)->create();
        $I->sendPOST('/api/auth/login', [
            'email' => $unverifiedUser->email,
            'password' => 'password'
        ]);
        $this->seeUnverifiedEmailError($I);

        /* Case: Wrong credential should return unauthorized response */
        $verifiedUser = factory(User::class)->create([
            'email_verified_at' => now()
        ]);
        $I->sendPOST('/api/auth/login', [
            'email' => $verifiedUser->email,
            'password' => 'wrongpassword'
        ]);
        $this->seeWrongCredentialOrInvalidAccountError($I);

        /* Case: Login successfully with correct email and password */
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

    public function register(ApiTester $I)
    {
        /* Case: Empty email should return validation error */
        $I->sendPOST('/api/auth/register', [
            'email' => '',
            'password' => 'anything',
            'password_confirmation' => 'anything'
        ]);
        $this->seeValidationError($I);

        /* Case: Empty password should return validation error */
        $I->sendPOST('/api/auth/register', [
            'email' => 'email@example.com',
            'password' => '',
            'password_confirmation' => 'anything'
        ]);
        $this->seeValidationError($I);

        /* Case: Empty password confirmation should return validation error */
        $I->sendPOST('/api/auth/register', [
            'email' => 'email@example.com',
            'password' => 'anything',
            'password_confirmation' => ''
        ]);
        $this->seeValidationError($I);

        /* Case: Email not valid should return validation error */
        $I->sendPOST('/api/auth/register', [
            'email' => 'invalid_email',
            'password' => 'anything',
            'password_confirmation' => 'anything'
        ]);
        $this->seeValidationError($I);

        /* Case: Not matching Password and Password confirmation should return validation error */
        $I->sendPOST('/api/auth/register', [
            'email' => 'email@example.com',
            'password' => 'anything',
            'password_confirmation' => 'anything1'
        ]);
        $this->seeValidationError($I);

        /* Case: Existing email should return validation error */
        $user = factory(User::class)->create([
            'email' => 'myemail@example.com',
        ]);
        $I->sendPOST('/api/auth/register', [
            'email' => 'myemail@example.com',
            'password' => 'anything',
            'password_confirmation' => 'anything'
        ]);
        $this->seeValidationError($I);

        /* Case: Register successfully */
        $I->sendPOST('/api/auth/register', [
            'email' => 'email@example.com',
            'password' => 'anything',
            'password_confirmation' => 'anything'
        ]);
        $I->seeResponseIsJson();
        $I->seeResponseCodeIs(Response::HTTP_OK);
    }

    /**
    * Depends on: login
    **/
    public function getUser(ApiTester $I)
    {
        /* Case: Cannot get user information when not logged in */
        $I->sendGET('/api/auth/getUser');
        $this->seeUnauthorizedRequestError($I);

        /* Case: Successfully get user information after login */
        // Login
        $verifiedUser = factory(User::class)->create([
            'email' => 'myemail@example.com',
            'email_verified_at' => now()
        ]);
        $I->sendPOST('/api/auth/login', [
            'email' => $verifiedUser->email,
            'password' => 'password'
        ]);
        // Get user
        $I->sendGET('/api/auth/getUser');
        $I->seeResponseIsJson();
        $I->seeResponseCodeIs(Response::HTTP_OK);
        $I->seeResponseContainsJson([
            'user' => [
              'email' => 'myemail@example.com'
            ]
        ]);
    }

    /**
    * Depends on: login, getUser
    **/
    public function logout(ApiTester $I)
    {
        /* Successfully get user information after login */
        // Login
        $verifiedUser = factory(User::class)->create([
            'email' => 'myemail@example.com',
            'email_verified_at' => now()
        ]);
        $I->sendPOST('/api/auth/login', [
            'email' => $verifiedUser->email,
            'password' => 'password'
        ]);
        // Get user
        $I->sendGET('/api/auth/getUser');
        $I->seeResponseIsJson();
        $I->seeResponseCodeIs(Response::HTTP_OK);
        $I->seeResponseContainsJson([
            'user' => [
              'email' => 'myemail@example.com'
            ]
        ]);
        /* Cannot get user information anymore after logout */
        // Do logout
        $I->sendGET('/api/auth/logout');
        $I->seeResponseCodeIs(Response::HTTP_NO_CONTENT);
        // Try getting user again
        $I->sendGET('/api/auth/getUser');
        $this->seeUnauthorizedRequestError($I);
    }

    private function seeUnauthorizedRequestError(ApiTester $I)
    {
        $I->seeResponseIsJson();
        $I->seeResponseContainsJson(['error' => ['code' => 'AUTH0010']]);
        $I->seeResponseCodeIs(Response::HTTP_UNAUTHORIZED);
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
