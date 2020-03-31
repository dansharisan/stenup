<?php
namespace Step\Api;

use Symfony\Component\HttpFoundation\Response as Response;

class CommonTest extends \ApiTester
{
    public function seeInvalidRolesPermissionsMatrixError()
    {
        $this->seeResponseIsJson();
        $this->seeResponseContainsJson(['error' => ['code' => 'AUTH0013']]);
        $this->seeResponseCodeIs(Response::HTTP_BAD_REQUEST);
    }

    public function seeInvalidRoleIDError()
    {
        $this->seeResponseIsJson();
        $this->seeResponseContainsJson(['error' => ['code' => 'AUTH0012']]);
        $this->seeResponseCodeIs(Response::HTTP_BAD_REQUEST);
    }

    public function seeInvalidTokenOrEmailError()
    {
        $this->seeResponseIsJson();
        $this->seeResponseContainsJson(['error' => ['code' => 'AUTH0006']]);
        $this->seeResponseCodeIs(Response::HTTP_BAD_REQUEST);
    }

    public function seeExpiredPasswordResetTokenError()
    {
        $this->seeResponseIsJson();
        $this->seeResponseContainsJson(['error' => ['code' => 'AUTH0005']]);
        $this->seeResponseCodeIs(Response::HTTP_BAD_REQUEST);
    }

    public function seeInvalidPasswordResetTokenError()
    {
        $this->seeResponseIsJson();
        $this->seeResponseContainsJson(['error' => ['code' => 'AUTH0004']]);
        $this->seeResponseCodeIs(Response::HTTP_BAD_REQUEST);
    }

    public function seeEmailNotFoundError()
    {
        $this->seeResponseIsJson();
        $this->seeResponseContainsJson(['error' => ['code' => 'AUTH0003']]);
        $this->seeResponseCodeIs(Response::HTTP_BAD_REQUEST);
    }

    public function seeInvalidTokenError()
    {
        $this->seeResponseIsJson();
        $this->seeResponseContainsJson(['error' => ['code' => 'AUTH0002']]);
        $this->seeResponseCodeIs(Response::HTTP_BAD_REQUEST);
    }

    public function seeUnauthorizedRequestError()
    {
        $this->seeResponseIsJson();
        $this->seeResponseContainsJson(['error' => ['code' => 'AUTH0010']]);
        $this->seeResponseCodeIs(Response::HTTP_UNAUTHORIZED);
    }

    public function seeUnverifiedEmailError()
    {
        $this->seeResponseIsJson();
        $this->seeResponseContainsJson(['error' => ['code' => 'AUTH0011']]);
        $this->seeResponseCodeIs(Response::HTTP_UNAUTHORIZED);
    }

    public function seeWrongCredentialOrInvalidAccountError()
    {
        $this->seeResponseIsJson();
        $this->seeResponseContainsJson(['error' => ['code' => 'AUTH0001']]);
        $this->seeResponseCodeIs(Response::HTTP_UNAUTHORIZED);
    }

    public function seeValidationError()
    {
        $this->seeResponseIsJson();
        $this->seeResponseContainsJson(['error' => ['code' => 'GENR0002']]);
        $this->seeResponseCodeIs(Response::HTTP_UNPROCESSABLE_ENTITY);
    }
}
