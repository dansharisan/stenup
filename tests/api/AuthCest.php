<?php
use Symfony\Component\HttpFoundation\Response as Response;
use App\Models\User;
use App\Models\PasswordReset;
use Carbon\Carbon;
use App\Http\Traits\UtilTrait;
use App\Enums\DefaultRoleType;
use App\Enums\PermissionType;
use Spatie\Permission\Models\Role;

class AuthCest
{
    use UtilTrait;
    /**
    * Endpoint: POST /api/auth/login
    **/
    public function login(ApiTester $I)
    {
        /* Case: Empty email should return validation error */
        $I->sendPOST('/api/auth/login', [
            'email' => '',
            'password' => 'anything'
        ]);
        $I->seeValidationError();

        /* Case: Email not valid should return validation error */
        $I->sendPOST('/api/auth/login', [
            'email' => 'invalid_email',
            'password' => 'anything'
        ]);
        $I->seeValidationError();

        /* Case: Empty password should return validation error */
        $I->sendPOST('/api/auth/login', [
            'email' => 'some_email@something.com',
            'password' => ''
        ]);
        $I->seeValidationError();

        /* Case: Unverified account should not be able to login */
        $unverifiedUser = factory(User::class)->create();
        $I->sendPOST('/api/auth/login', [
            'email' => $unverifiedUser->email,
            'password' => 'password'
        ]);
        $I->seeUnverifiedEmailError();

        /* Case: Wrong credential should return unauthorized response */
        $verifiedUser = factory(User::class)->create([
            'email_verified_at' => now()
        ]);
        $I->sendPOST('/api/auth/login', [
            'email' => $verifiedUser->email,
            'password' => 'wrongpassword'
        ]);
        $I->seeWrongCredentialOrInvalidAccountError();

        /* Case: Login successfully with correct email and password */
        $I->sendPOST('/api/auth/login', [
            'email' => $verifiedUser->email,
            'password' => 'password'
        ]);
        $I->seeResponseIsJson();
        $I->seeResponseCodeIs(Response::HTTP_OK);
    }

    /**
    * Endpoint: POST /api/auth/register
    **/
    public function register(ApiTester $I)
    {
        /* Case: Empty email should return validation error */
        $I->sendPOST('/api/auth/register', [
            'email' => '',
            'password' => 'anything',
            'password_confirmation' => 'anything'
        ]);
        $I->seeValidationError();

        /* Case: Empty password should return validation error */
        $I->sendPOST('/api/auth/register', [
            'email' => 'email@example.com',
            'password' => '',
            'password_confirmation' => 'anything'
        ]);
        $I->seeValidationError();

        /* Case: Empty password confirmation should return validation error */
        $I->sendPOST('/api/auth/register', [
            'email' => 'email@example.com',
            'password' => 'anything',
            'password_confirmation' => ''
        ]);
        $I->seeValidationError();

        /* Case: Email not valid should return validation error */
        $I->sendPOST('/api/auth/register', [
            'email' => 'invalid_email',
            'password' => 'anything',
            'password_confirmation' => 'anything'
        ]);
        $I->seeValidationError();

        /* Case: Not matching Password and Password confirmation should return validation error */
        $I->sendPOST('/api/auth/register', [
            'email' => 'email@example.com',
            'password' => 'anything',
            'password_confirmation' => 'anything1'
        ]);
        $I->seeValidationError();

        /* Case: Existing email should return validation error */
        $user = factory(User::class)->create([
            'email' => 'myemail@example.com',
        ]);
        $I->sendPOST('/api/auth/register', [
            'email' => 'myemail@example.com',
            'password' => 'anything',
            'password_confirmation' => 'anything'
        ]);
        $I->seeValidationError();

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
    * Endpoint: GET /api/auth/getUser
    * Depends on: login
    **/
    public function getUser(ApiTester $I)
    {
        /* Case: Cannot get user information when not logged in */
        $I->sendGET('/api/auth/getUser');
        $I->seeUnauthorizedRequestError();

        /* Case: Successfully get user information after login */
        // Login
        $verifiedUser = factory(User::class)->create([
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
              'email' => $verifiedUser->email
            ]
        ]);
    }

    /**
    * Endpoint: GET /api/auth/logout
    * Depends on: login, getUser
    **/
    public function logout(ApiTester $I)
    {
        /* Successfully get user information after login */
        // Login
        $verifiedUser = factory(User::class)->create([
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
              'email' => $verifiedUser->email
            ]
        ]);
        /* Cannot get user information anymore after logout */
        // Do logout
        $I->sendGET('/api/auth/logout');
        $I->seeResponseCodeIs(Response::HTTP_NO_CONTENT);
        // Try getting user again
        $I->sendGET('/api/auth/getUser');
        $I->seeUnauthorizedRequestError();
    }

    /**
    * Endpoint: GET /api/auth/register/activate/{token}
    **/
    public function activate(ApiTester $I)
    {
        // Prepare data: an unactivated user
        $activationToken = $this->quickRandom(60);
        $verifiedUser = factory(User::class)->create([
            'activation_token' => $activationToken
        ]);

        /* Case: Wrong activation token should return error */
        $I->sendGET('/api/auth/register/activate/' . 'non_existing_token');
        $I->seeInvalidTokenError();

        /* Case: With correct token, activate user successfully */
        $I->sendGET('/api/auth/register/activate/' . $activationToken);
        $I->seeResponseIsJson();
        $I->seeResponseCodeIs(Response::HTTP_OK);
        $I->seeResponseContainsJson([
            'user' => [
              'email' => $verifiedUser->email
            ]
        ]);
    }

    /**
    * Endpoint: POST /api/auth/password/token/create
    **/
    public function createPasswordResetToken(ApiTester $I)
    {
        /* Case: Empty email should return validation error */
        $I->sendPOST('/api/auth/password/token/create', [
            'email' => ''
        ]);
        $I->seeValidationError();

        /* Case: Email not valid should return validation error */
        $I->sendPOST('/api/auth/password/token/create', [
            'email' => 'invalid_email'
        ]);
        $I->seeValidationError();

        /* Case: Non-existing email should return bad request error */
        $I->sendPOST('/api/auth/password/token/create', [
            'email' => 'non_existing_email@example.com'
        ]);
        $I->seeEmailNotFoundError();

        /* Case: Existing email return a success response */
        $email = 'real_email@example.com';
        factory(User::class)->create([
            'email_verified_at' => now(),
            'email' => $email
        ]);
        $I->sendPOST('/api/auth/password/token/create', [
            'email' => $email
        ]);
        $I->seeResponseCodeIs(Response::HTTP_NO_CONTENT);
    }

    /**
    * Endpoint: GET /api/auth/password/token/find/{token}
    **/
    public function findPasswordResetToken(ApiTester $I)
    {
        /* Case: non-existing token should return invalid password reset token error */
        $token = 'non_existing_token';
        $I->sendGET('/api/auth/password/token/find/' . $token);
        $I->seeInvalidPasswordResetTokenError();

        /* Case: expired token should return expired password reset token error */
        $passwordReset = factory(PasswordReset::class)->create([
            'updated_at' => Carbon::parse(now())->addMinutes(0 - PasswordReset::PASSWORD_RESET_TOKEN_TIME_VALIDITY_IN_MINUTE - 1)
        ]);
        $I->sendGET('/api/auth/password/token/find/' . $passwordReset->token);
        $I->seeExpiredPasswordResetTokenError();

        /* Case: valid token should return success response */
        $passwordReset = factory(PasswordReset::class)->create([
            'updated_at' => now()
        ]);
        $I->sendGET('/api/auth/password/token/find/' . $passwordReset->token);
        $I->seeResponseCodeIs(Response::HTTP_OK);
    }

    /**
    * Endpoint: PATCH /api/auth/password/reset
    * Depends on: login
    **/
    public function resetPassword(ApiTester $I)
    {
        // Prepare data
        $user = factory(User::class)->create([
            'email_verified_at' => now()
        ]);
        $passwordReset = factory(PasswordReset::class)->create([
            'updated_at' => now(),
            'email' => $user->email
        ]);
        $nonExistentUserPasswordReset = factory(PasswordReset::class)->create([
            'updated_at' => now(),
        ]);

        /* Case: Empty email should return validation error */
        $I->sendPATCH('/api/auth/password/reset', [
            'email' => '',
            'password' => 'password',
            'password_confirmation' => 'password',
            'token' => $passwordReset->token
        ]);
        $I->seeValidationError();

        /* Case: Email not valid should return validation error */
        $I->sendPATCH('/api/auth/password/reset', [
            'email' => 'invalid_email',
            'password' => 'password',
            'password_confirmation' => 'password',
            'token' => $passwordReset->token
        ]);
        $I->seeValidationError();

        /* Case: Empty password should return validation error */
        $I->sendPATCH('/api/auth/password/reset', [
            'email' => $passwordReset->email,
            'password' => '',
            'password_confirmation' => 'password',
            'token' => $passwordReset->token
        ]);
        $I->seeValidationError();

        /* Case: Not matching Password and Password confirmation should return validation error */
        $I->sendPATCH('/api/auth/password/reset', [
            'email' => $passwordReset->email,
            'password' => 'password',
            'password_confirmation' => 'password1',
            'token' => $passwordReset->token
        ]);
        $I->seeValidationError();

        /* Case: Empty token should return validation error */
        $I->sendPATCH('/api/auth/password/reset', [
            'email' => $passwordReset->email,
            'password' => 'password',
            'password_confirmation' => 'password',
            'token' => ''
        ]);
        $I->seeValidationError();

        /* Case: Incorrect token should return invalid token or email error */
        $I->sendPATCH('/api/auth/password/reset', [
            'email' => $passwordReset->email,
            'password' => 'password',
            'password_confirmation' => 'password',
            'token' => 'non_existing_token'
        ]);
        $I->seeInvalidTokenOrEmailError();

        /* Case: Incorrect email should return invalid token or email error */
        $I->sendPATCH('/api/auth/password/reset', [
            'email' => 'fake_email@example.com',
            'password' => 'password',
            'password_confirmation' => 'password',
            'token' => $passwordReset->token
        ]);
        $I->seeInvalidTokenOrEmailError();

        /* Case: Non-existent user email should return email not found error */
        $I->sendPATCH('/api/auth/password/reset', [
            'email' => $nonExistentUserPasswordReset->email,
            'password' => 'password',
            'password_confirmation' => 'password',
            'token' => $nonExistentUserPasswordReset->token
        ]);
        $I->seeEmailNotFoundError();

        /* Case: Successfully reset password with correct provided information */
        $newPassword = 'new_password';
        $I->sendPATCH('/api/auth/password/reset', [
            'email' => $passwordReset->email,
            'password' => $newPassword,
            'password_confirmation' => $newPassword,
            'token' => $passwordReset->token
        ]);
        $I->seeResponseIsJson();
        $I->seeResponseCodeIs(Response::HTTP_OK);
        $I->seeResponseContainsJson([
            'user' => [
              'email' => $passwordReset->email
            ]
        ]);
        // That Password Reset token should be deleted right after that process is done
        $I->sendPATCH('/api/auth/password/reset', [
            'email' => $passwordReset->email,
            'password' => $newPassword,
            'password_confirmation' => $newPassword,
            'token' => $passwordReset->token
        ]);
        $I->seeInvalidTokenOrEmailError();
        // Should be able to login with the new password
        $I->sendPOST('/api/auth/login', [
            'email' => $user->email,
            'password' => $newPassword
        ]);
        $I->seeResponseIsJson();
        $I->seeResponseCodeIs(Response::HTTP_OK);
    }

    /**
    * Endpoint: PATCH /api/auth/password/change
    * Depends on: login
    **/
    public function changePassword(ApiTester $I)
    {
        // Prepare data
        $user = factory(User::class)->create([
            'email_verified_at' => now()
        ]);
        $currentPassword = 'password'; // Current password is 'password' by default (see UserFactory)
        $newPassword = 'new_password';

        /* Case: Calling the API while not logged in should return unauthorized error */
        $currentPassword = 'password'; // Current password is 'password' by default (see UserFactory)
        $newPassword = 'new_password';
        $I->sendPATCH('/api/auth/password/change', [
            'password' => $currentPassword,
            'new_password' => $newPassword,
            'new_password_confirmation' => $newPassword
        ]);
        $I->seeUnauthorizedRequestError();

        // Now we need to login to do the rest of tests
        $I->sendPOST('/api/auth/login', [
            'email' => $user->email,
            'password' => $currentPassword
        ]);

        /* Case: Empty password should return validation error */
        $I->sendPATCH('/api/auth/password/change', [
            'password' => '',
            'new_password' => $newPassword,
            'new_password_confirmation' => $newPassword
        ]);
        $I->seeValidationError();

        /* Case: Empty new password should return validation error */
        $I->sendPATCH('/api/auth/password/change', [
            'password' => $currentPassword,
            'new_password' => '',
            'new_password_confirmation' => $newPassword
        ]);
        $I->seeValidationError();

        /* Case: Not matching New Password and New Password Confirmation should return validation error */
        $I->sendPATCH('/api/auth/password/change', [
            'password' => $currentPassword,
            'new_password' => $newPassword,
            'new_password_confirmation' => $newPassword . '1'
        ]);
        $I->seeValidationError();

        /* Case: Wrong credential should return unauthorized response */
        $I->sendPATCH('/api/auth/password/change', [
            'password' => 'wrong_password',
            'new_password' => $newPassword,
            'new_password_confirmation' => $newPassword
        ]);
        $I->seeWrongCredentialOrInvalidAccountError();

        /* Case: Successfully change password with proper information */
        $I->sendPATCH('/api/auth/password/change', [
            'password' => $currentPassword,
            'new_password' => $newPassword,
            'new_password_confirmation' => $newPassword
        ]);
        $I->seeResponseIsJson();
        $I->seeResponseCodeIs(Response::HTTP_OK);
        $I->seeResponseContainsJson([
            'user' => [
              'email' => $user->email
            ]
        ]);
        // Should be able to login with the new password
        $I->sendPOST('/api/auth/login', [
            'email' => $user->email,
            'password' => $newPassword
        ]);
        $I->seeResponseIsJson();
        $I->seeResponseCodeIs(Response::HTTP_OK);
    }

    /**
    * Endpoint: GET /api/auth/roles_permissions
    * Depends on: login
    **/
    public function getRolesAndPermissions(ApiTester $I) {
        // Prepare data
        $memberUser = $I->generateMemberUser();

        /* Case: Calling the API while not logged in should return unauthorized error */
        $I->sendGET('/api/auth/roles_permissions');
        $I->seeUnauthorizedRequestError();

        /* Case: By default, member user, which normally don't have VIEW_ROLES_PERMISSIONS permission, shouldn't be able to access this API */
        $I->sendPOST('/api/auth/login', [
            'email' => $memberUser->email,
            'password' => 'password'
        ]);
        $I->sendGET('/api/auth/roles_permissions');
        $I->seeUnauthorizedRequestError();

        /* Case: When that user is set to have VIEW_ROLES_PERMISSIONS permission, he could get access to this API */
        $memberUser->roles[0]->givePermissionTo(PermissionType::VIEW_ROLES_PERMISSIONS);
        $I->sendGET('/api/auth/roles_permissions');
        $I->seeResponseIsJson();
        $I->seeResponseCodeIs(Response::HTTP_OK);
        // Make sure we have the expected data
        $I->seeResponseContainsJson(
            [
                'roles' => [
                                [
                                    'name' => DefaultRoleType::ADMINISTRATOR
                                ],
                                [
                                    'name' => DefaultRoleType::MEMBER
                                ]
                            ],
                'permissions' => [
                                    [
                                        'name' => PermissionType::VIEW_ROLES_PERMISSIONS
                                    ],
                                    [
                                        'name' => PermissionType::VIEW_USERS
                                    ],
                            ]
            ]
        );
    }

    /**
    * Endpoint: GET /api/auth/roles_w_permissions
    * Depends on: login
    **/
    public function getRolesWithPermissions(ApiTester $I) {
        // Prepare data
        $memberUser = $I->generateMemberUser();

        /* Case: Calling the API while not logged in should return unauthorized error */
        $I->sendGET('/api/auth/roles_w_permissions');
        $I->seeUnauthorizedRequestError();

        /* Case: By default, member user, which normally don't have VIEW_ROLES_PERMISSIONS permission, shouldn't be able to access this API */
        $I->sendPOST('/api/auth/login', [
            'email' => $memberUser->email,
            'password' => 'password'
        ]);
        $I->sendGET('/api/auth/roles_w_permissions');
        $I->seeUnauthorizedRequestError();

        /* Case: When that user is set to have VIEW_ROLES_PERMISSIONS permission, he could get access to this API */
        $memberUser->roles[0]->givePermissionTo(PermissionType::VIEW_ROLES_PERMISSIONS);
        $I->sendGET('/api/auth/roles_w_permissions');
        $I->seeResponseIsJson();
        $I->seeResponseCodeIs(Response::HTTP_OK);
        // Make sure we have the expected data
        $I->seeResponseContainsJson(
            [
                'roles' => [
                                [
                                    'name' => DefaultRoleType::ADMINISTRATOR,
                                    'permissions' => [
                                        [
                                            'name' => PermissionType::VIEW_DASHBOARD
                                        ],
                                    ]
                                ],
                                [
                                    'name' => DefaultRoleType::MEMBER,
                                    'permissions' => [
                                        [
                                            'name' => PermissionType::VIEW_ROLES_PERMISSIONS
                                        ],
                                    ]
                                ],
                            ]
            ]
        );
    }

    /**
    * Endpoint: POST /api/auth/roles
    * Depends on: login
    **/
    public function createRole(ApiTester $I) {
        // Prepare data
        $memberUser = $I->generateMemberUser();

        /* Case: Calling the API while not logged in should return unauthorized error */
        $I->sendPOST('/api/auth/roles' , [
            'role_name' => 'New role'
        ]);
        $I->seeUnauthorizedRequestError();

        /* Case: By default, member user, which normally don't have CREATE_ROLES permission, shouldn't be able to access this API */
        $I->sendPOST('/api/auth/login', [
            'email' => $memberUser->email,
            'password' => 'password'
        ]);
        $I->sendPOST('/api/auth/roles' , [
            'role_name' => 'New role'
        ]);
        $I->seeUnauthorizedRequestError();

        // When that user is set to have CREATE_ROLES permission, he could get access to this API //
        $memberUser->roles[0]->givePermissionTo(PermissionType::CREATE_ROLES);
        /* Case: Empty role_name should return validation error */
        $I->sendPOST('/api/auth/roles' , [
            'role_name' => ''
        ]);
        $I->seeValidationError();

        /* Case: Existing role_name should return validation error */
        $I->sendPOST('/api/auth/roles' , [
            'role_name' => DefaultRoleType::MEMBER
        ]);
        $I->seeValidationError();

        /* Case: Successfully create a new role */
        $newRoleName = 'New role';
        $I->sendPOST('/api/auth/roles' , [
            'role_name' => $newRoleName
        ]);
        $I->seeResponseIsJson();
        $I->seeResponseCodeIs(Response::HTTP_OK);
        // Make sure we have the expected data
        $I->seeResponseContainsJson(
            [
                'role' => [
                    'name' => $newRoleName,
                ],
            ]
        );
    }

    /**
    * Endpoint: DELETE /api/auth/roles/{id}
    * Depends on: login
    **/
    public function deleteRole(ApiTester $I) {
        // Prepare data
        $newRole = factory(Role::class)->create();
        $memberUser = $I->generateMemberUser();

        /* Case: Calling the API while not logged in should return unauthorized error */
        $I->sendDELETE('/api/auth/roles/' . $newRole->id);
        $I->seeUnauthorizedRequestError();

        /* Case: By default, member user, which normally don't have DELETE_ROLES permission, shouldn't be able to access this API */
        $I->sendPOST('/api/auth/login', [
            'email' => $memberUser->email,
            'password' => 'password'
        ]);
        $I->sendDELETE('/api/auth/roles/' . $newRole->id);
        $I->seeUnauthorizedRequestError();

        // When that user is set to have DELETE_ROLES permission, he could get access to this API //
        $memberUser->roles[0]->givePermissionTo(PermissionType::DELETE_ROLES);
        /* Case: If 0 is provided, it should return invalid role id error */
        $I->sendDELETE('/api/auth/roles/' . '0');
        $I->seeInvalidRoleIDError();

        /* Case: If non-existent ID is provided, it should return invalid role id error */
        $nonExistentRoleId = 999;
        $I->sendDELETE('/api/auth/roles/' . $nonExistentRoleId);
        $I->seeInvalidRoleIDError();

        /* Case: Successfully delete the role*/
        $I->sendDELETE('/api/auth/roles/' . $newRole->id);
        $I->seeResponseCodeIs(Response::HTTP_NO_CONTENT);
    }

    /**
    * Endpoint: PUT /api/auth/update_roles_permissions_matrix
    * Depends on: login
    **/
    public function updateRolesPermissionsMatrix(ApiTester $I) {
        // Prepare data
        $memberUser = $I->generateMemberUser();

        /* Case: Calling the API while not logged in should return unauthorized error */
        $dumpMatrix = '{"'. DefaultRoleType::MEMBER . '":["' . PermissionType::VIEW_DASHBOARD . '","' . PermissionType::VIEW_ROLES_PERMISSIONS . '"], "' . DefaultRoleType::ADMINISTRATOR . '":["' . PermissionType::UPDATE_PERMISSIONS . '"]}';
        $I->sendPUT('/api/auth/update_roles_permissions_matrix', [
            'matrix' => $dumpMatrix
        ]);
        $I->seeUnauthorizedRequestError();

        /* Case: By default, member user, which normally don't have UPDATE_PERMISSIONS permission, shouldn't be able to access this API */
        $I->sendPOST('/api/auth/login', [
            'email' => $memberUser->email,
            'password' => 'password'
        ]);
        $I->sendPUT('/api/auth/update_roles_permissions_matrix', [
            'matrix' => $dumpMatrix
        ]);
        $I->seeUnauthorizedRequestError();

        // When that user is set to have DELETE_ROLES permission, he could get access to this API //
        $memberUser->roles[0]->givePermissionTo(PermissionType::UPDATE_PERMISSIONS);
        /* Case: Empty matrix should return validation error */
        $I->sendPUT('/api/auth/update_roles_permissions_matrix', [
            'matrix' => ''
        ]);
        $I->seeValidationError();

        /* Case: Invalid matrix will return validation error */
        $invalidMatrix = '[Invalid}.';
        $I->sendPUT('/api/auth/update_roles_permissions_matrix', [
            'matrix' => $invalidMatrix
        ]);
        $I->seeInvalidRolesPermissionsMatrixError();

        /* Successfully apply the matrix */
        $I->sendPUT('/api/auth/update_roles_permissions_matrix', [
            'matrix' => $dumpMatrix
        ]);
        $I->seeResponseIsJson();
        $I->seeResponseCodeIs(Response::HTTP_OK);
        // Make sure we have the expected data
        $I->seeResponseContainsJson(
            [
                'roles' => [
                                [
                                    'name' => DefaultRoleType::ADMINISTRATOR,
                                    'permissions' => [
                                        [
                                            'name' => PermissionType::UPDATE_PERMISSIONS
                                        ],
                                    ]
                                ],
                                [
                                    'name' => DefaultRoleType::MEMBER,
                                    'permissions' => [
                                        [
                                            'name' => PermissionType::VIEW_DASHBOARD
                                        ],
                                        [
                                            'name' => PermissionType::VIEW_ROLES_PERMISSIONS
                                        ],
                                    ]
                                ],
                            ]
            ]
        );
        // After updating, this user should not be able to access this api anymore
        $I->sendPUT('/api/auth/update_roles_permissions_matrix', [
            'matrix' => $dumpMatrix
        ]);
        $I->seeUnauthorizedRequestError();
    }
}
