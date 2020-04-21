<?php

namespace App\Http\Controllers;

use Validator;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cookie;
use Symfony\Component\HttpFoundation\Response as Response;
use Illuminate\Support\Facades\DB;
use App\Http\Traits as Traits;
use App\Notifications as Notifications;
use Spatie\Permission\Models as SpatiePermissionModels;
use App\Enums as Enums;
use App\Models as Models;

class AuthController extends Controller
{
    use Traits\ResponseTrait, Traits\UtilTrait;

    /**
    * @OA\Post(
    *         path="/api/auth/register",
    *         tags={"Auth"},
    *         summary="Register",
    *         description="Register a new user and send notification mail",
    *         operationId="register",
    *         @OA\Response(
    *             response=200,
    *             description="Successful operation"
    *         ),
    *         @OA\Response(
    *             response=422,
    *             description="Validation error"
    *         ),
    *         @OA\Response(
    *             response=500,
    *             description="Server error"
    *         ),
    *         @OA\RequestBody(
    *             required=true,
    *             @OA\MediaType(
    *                 mediaType="application/x-www-form-urlencoded",
    *                 @OA\Schema(
    *                     type="object",
    *                     @OA\Property(
    *                         property="email",
    *                         description="Email",
    *                         type="string",
    *                     ),
    *                     @OA\Property(
    *                         property="password",
    *                         description="Password",
    *                         type="string",
    *                         format="password"
    *                     ),
    *                     @OA\Property(
    *                         property="password_confirmation",
    *                         description="Confirm password",
    *                         type="string",
    *                         format="password"
    *                     )
    *                 )
    *             )
    *         )
    * )
    */
    public function register(Request $request)
    {
        // Validate input data
        $validator = Validator::make($request->all(), [
            'email' => 'required|string|email|unique:users',
            'password' => 'required|string',
            'password_confirmation' => 'required|string|same:password'
        ]);
        if ($validator->fails()) {
            return response()->json(
            [
                'error' =>
                        [
                            'code' => Enums\ErrorEnum::GENR0002,
                            'message' => Enums\ErrorEnum::getDescription(Enums\ErrorEnum::GENR0002)
                        ],
                'validation' => $validator->errors()
            ],
            Response::HTTP_UNPROCESSABLE_ENTITY);
        }

        // Create user
        $user = new Models\User([
            'email' => $request->email,
            'password' => bcrypt($request->password),
            'activation_token' => $this->quickRandom(60)
        ]);
        $user->save();

        // Default role
        $user->assignRole(Enums\DefaultRoleEnum::MEMBER);

        // Send email with activation link
        $user->notify(new Notifications\RegisterActivateNotification($user));

        return response()->json(['user' => $user], Response::HTTP_OK);
    }

    /**
    * @OA\Post(
    *         path="/api/auth/register/resend_activation_email",
    *         tags={"Auth"},
    *         summary="Resend activation email",
    *         description="Resend activation email",
    *         operationId="resend-activation-email",
    *         @OA\Response(
    *             response=204,
    *             description="Successful operation with no content in return"
    *         ),
    *         @OA\Response(
    *             response=422,
    *             description="Validation error"
    *         ),
    *         @OA\Response(
    *             response=500,
    *             description="Server error"
    *         ),
    *         @OA\RequestBody(
    *             required=true,
    *             @OA\MediaType(
    *                 mediaType="application/x-www-form-urlencoded",
    *                 @OA\Schema(
    *                     type="object",
    *                     @OA\Property(
    *                         property="email",
    *                         description="Email",
    *                         type="string",
    *                     )
    *                 )
    *             )
    *         )
    * )
    */
    public function resendActivationEmail(Request $request)
    {
        // Validate input data
        $validator = Validator::make($request->all(), [
            'email' => 'required|string|email'
        ]);
        if ($validator->fails()) {
            return response()->json(
            [
                'error' =>
                        [
                            'code' => Enums\ErrorEnum::GENR0002,
                            'message' => Enums\ErrorEnum::getDescription(Enums\ErrorEnum::GENR0002)
                        ],
                'validation' => $validator->errors()
            ],
            Response::HTTP_UNPROCESSABLE_ENTITY);
        }

        // Find user with that email
        $user = Models\User::firstWhere('email', $request->email);

        // Pretend we've sent email (for security concern)
        if (!$user || !$user->activation_token) {
            return response()->json(null, Response::HTTP_NO_CONTENT);
        }

        // Generate new token
        $user->activation_token = $this->quickRandom(60);
        $user->save();

        // Send email with activation link
        $user->notify(new Notifications\RegisterActivateNotification($user));

        return response()->json(null, Response::HTTP_NO_CONTENT);
    }

    /**
    * @OA\Post(
    *         path="/api/auth/login",
    *         tags={"Auth"},
    *         summary="Login",
    *         description="Login an user",
    *         operationId="login",
    *         @OA\Response(
    *             response=200,
    *             description="Successful operation"
    *         ),
    *         @OA\Response(
    *             response=422,
    *             description="Validation error"
    *         ),
    *         @OA\Response(
    *             response=403,
    *             description="Wrong combination of email and password or email not verified"
    *         ),
    *         @OA\Response(
    *             response=500,
    *             description="Server error"
    *         ),
    *         @OA\RequestBody(
    *             required=true,
    *             @OA\MediaType(
    *                 mediaType="application/x-www-form-urlencoded",
    *                 @OA\Schema(
    *                     type="object",
    *                      @OA\Property(
    *                         property="email",
    *                         description="Email",
    *                         type="string",
    *                     ),
    *                     @OA\Property(
    *                         property="password",
    *                         description="Password",
    *                         type="string",
    *                         format="password"
    *                     ),
    *                 )
    *             )
    *         )
    * )
    */
    public function login(Request $request)
    {
        // Validate input data
        $validator = Validator::make($request->all(), [
            'email' => 'required|string|email',
            'password' => 'required|string',
        ]);
        if ($validator->fails()) {
            return response()->json(
            [
                'error' =>
                        [
                            'code' => Enums\ErrorEnum::GENR0002,
                            'message' => Enums\ErrorEnum::getDescription(Enums\ErrorEnum::GENR0002)
                        ],
                'validation' => $validator->errors()
            ],
            Response::HTTP_UNPROCESSABLE_ENTITY);
        }

        $credentials = request(['email', 'password']);
        $credentials['status'] = Enums\UserStatusEnum::Active;
        $credentials['deleted_at'] = null;

        // Check the combination of email and password, also check for active status
        if(!$token = auth('api')->attempt($credentials)) {
            return response()->json(
                ['error' =>
                            [
                                'code' => Enums\ErrorEnum::AUTH0001,
                                'message' => Enums\ErrorEnum::getDescription(Enums\ErrorEnum::AUTH0001)
                            ]
                ], Response::HTTP_UNAUTHORIZED
            );
        }

        $user = auth('api')->user();
        // Check if the email is verified
        if (!$user->email_verified_at) {
            return response()->json(
                ['error' =>
                            [
                                'code' => Enums\ErrorEnum::AUTH0011,
                                'message' => Enums\ErrorEnum::getDescription(Enums\ErrorEnum::AUTH0011)
                            ]
                ], Response::HTTP_UNAUTHORIZED
            );
        }

        // Get roles and permissions
        $user['associated_permissions'] = $user->getAllPermissions();
        $user['roles'] = $user->getRoleNames();

        return response()->json(['user' => $user], Response::HTTP_OK)->withCookie('token', $token, config('jwt.ttl'), "/", null, false, true);
    }

    /**
    * @OA\Get(
    *         path="/api/auth/logout",
    *         tags={"Auth"},
    *         summary="Logout",
    *         description="Logout an user",
    *         operationId="logout",
    *         @OA\Response(
    *             response=204,
    *             description="Successful operation with no content in return"
    *         ),
    *         @OA\Response(
    *             response=500,
    *             description="Server error"
    *         ),
    * )
    */
    public function logout(Request $request)
    {
        auth('api')->logout();

        return response()->json(null, Response::HTTP_NO_CONTENT)->withCookie(Cookie::forget('token'));
    }

    /**
    * @OA\Get(
    *         path="/api/auth/getUser",
    *         tags={"Auth"},
    *         summary="Get user",
    *         description="Retrieve information from current user",
    *         operationId="getUser",
    *         @OA\Response(
    *             response=200,
    *             description="Successful operation"
    *         ),
    *         @OA\Response(
    *             response=500,
    *             description="Server error"
    *         ),
    * )
    */
    public function getUser(Request $request)
    {
        $user = $request->user();

        // Get roles and permissions
        $user['associated_permissions'] = $user->getAllPermissions();
        $user->getRoleNames();

        return response()->json(['user' => $user], Response::HTTP_OK);
    }

    /**
    * @OA\Get(
    *         path="/api/auth/register/activate/{token}",
    *         tags={"Auth"},
    *         summary="Activate user",
    *         description="Activate an registered user",
    *         operationId="activateUser",
    *         @OA\Parameter(
    *             name="token",
    *             in="path",
    *             description="User activating token (should be included in the verification mail)",
    *             required=true,
    *             @OA\Schema(
    *                 type="string",
    *             )
    *         ),
    *         @OA\Response(
    *             response=204,
    *             description="Successful operation with no content in return"
    *         ),
    *         @OA\Response(
    *             response=400,
    *             description="Invalid input data"
    *         ),
    *         @OA\Response(
    *             response=500,
    *             description="Server error"
    *         ),
    * )
    */
    public function activate($token)
    {
        $user = Models\User::where('activation_token', $token)->first();
        // If the token is not existing, throw error
        if (!$user) {
            return response()->json(
                ['error' =>
                            [
                                'code' => Enums\ErrorEnum::AUTH0002,
                                'message' => Enums\ErrorEnum::getDescription(Enums\ErrorEnum::AUTH0002)
                            ]
                ], Response::HTTP_BAD_REQUEST
            );
        }
        // Update activation info
        $user->activation_token = null;
        $user->email_verified_at = Carbon::now();
        $user->save();

        return response()->json(null, Response::HTTP_NO_CONTENT);
    }

    /**
    * @OA\Post(
    *         path="/api/auth/password/token/create",
    *         tags={"Auth"},
    *         summary="Request resetting password",
    *         description="Generate password reset token and send that token to user through mail",
    *         operationId="createPasswordResetToken",
    *         @OA\Response(
    *             response=204,
    *             description="Successful operation with no content in return"
    *         ),
    *         @OA\Response(
    *             response=400,
    *             description="Invalid input data"
    *         ),
    *         @OA\Response(
    *             response=422,
    *             description="Validation error"
    *         ),
    *         @OA\Response(
    *             response=500,
    *             description="Server error"
    *         ),
    *         @OA\RequestBody(
    *             required=true,
    *             @OA\MediaType(
    *                 mediaType="application/x-www-form-urlencoded",
    *                 @OA\Schema(
    *                     type="object",
    *                     @OA\Property(
    *                         property="email",
    *                         description="Email",
    *                         type="string",
    *                     ),
    *                 )
    *             )
    *         )
    * )
    */
    public function createPasswordResetToken(Request $request)
    {
        // Validate input data
        $validator = Validator::make($request->all(), [
            'email' => 'required|string|email',
        ]);
        if ($validator->fails()) {
            return response()->json(
            [
                'error' =>
                        [
                            'code' => Enums\ErrorEnum::GENR0002,
                            'message' => Enums\ErrorEnum::getDescription(Enums\ErrorEnum::GENR0002)
                        ],
                'validation' => $validator->errors()
            ],
            Response::HTTP_UNPROCESSABLE_ENTITY);
        }

        $user = Models\User::firstWhere('email', $request->email);
        // If the email is not existing, pretends it's a successful request, but do nothing (for security concern)
        if (!$user) {
            return response()->json(null, Response::HTTP_NO_CONTENT);
        }
        // Create or update token
        $passwordReset = Models\PasswordReset::updateOrCreate(
            ['email' => $user->email],
            [
                'email' => $user->email,
                'token' => $this->quickRandom(60)
            ]
        );
        if ($user && $passwordReset) {
            $user->notify(new Notifications\PasswordResetRequestNotification($passwordReset->token));
        }

        return response()->json(null, Response::HTTP_NO_CONTENT);
    }

    /**
    * @OA\Get(
    *         path="/api/auth/password/token/find/{token}",
    *         tags={"Auth"},
    *         summary="Verify reset password token",
    *         description="Verify the reset password token and make sure it is existing and still valid",
    *         operationId="findPasswordResetToken",
    *         @OA\Parameter(
    *             name="token",
    *             in="path",
    *             description="Password reset token (should be included in the notification mail)",
    *             required=true,
    *             @OA\Schema(
    *                 type="string",
    *             )
    *         ),
    *         @OA\Response(
    *             response=204,
    *             description="Successful operation with no content in return"
    *         ),
    *         @OA\Response(
    *             response=400,
    *             description="Invalid input data"
    *         ),
    *         @OA\Response(
    *             response=500,
    *             description="Server error"
    *         ),
    * )
    */
    public function findPasswordResetToken($token)
    {
        // Make sure the password reset token is findable, otherwise throw error
        $passwordReset = Models\PasswordReset::where('token', $token)->first();
        if (!$passwordReset) {
            return response()->json(
                ['error' =>
                            [
                                'code' => Enums\ErrorEnum::AUTH0004,
                                'message' => Enums\ErrorEnum::getDescription(Enums\ErrorEnum::AUTH0004)
                            ]
                ], Response::HTTP_BAD_REQUEST
            );
        }

        if (Carbon::parse($passwordReset->updated_at)->addMinutes(\Config::get('auth.passwords.users.expire'))->isPast()) {
            $passwordReset->delete();
            return response()->json(
                ['error' =>
                            [
                                'code' => Enums\ErrorEnum::AUTH0005,
                                'message' => Enums\ErrorEnum::getDescription(Enums\ErrorEnum::AUTH0005)
                            ]
                ], Response::HTTP_BAD_REQUEST
            );
        }

        return response()->json(null, Response::HTTP_NO_CONTENT);
    }

    /**
    * @OA\Patch(
    *         path="/api/auth/password/reset",
    *         tags={"Auth"},
    *         summary="Reset password",
    *         description="Set new password for the user",
    *         operationId="resetPassword",
    *         @OA\Response(
    *             response=200,
    *             description="Successful operation"
    *         ),
    *         @OA\Response(
    *             response=400,
    *             description="Password reset token invalid or email not existing"
    *         ),
    *         @OA\Response(
    *             response=422,
    *             description="Validation error"
    *         ),
    *         @OA\Response(
    *             response=500,
    *             description="Server error"
    *         ),
    *         @OA\RequestBody(
    *             required=true,
    *             @OA\MediaType(
    *                 mediaType="application/x-www-form-urlencoded",
    *                 @OA\Schema(
    *                     type="object",
    *                     @OA\Property(
    *                         property="email",
    *                         description="Email",
    *                         type="string",
    *                     ),
    *                     @OA\Property(
    *                         property="password",
    *                         description="Password",
    *                         type="string",
    *                         format="password"
    *                     ),
    *                     @OA\Property(
    *                         property="password_confirmation",
    *                         description="Confirm password",
    *                         type="string",
    *                         format="password"
    *                     ),
    *                     @OA\Property(
    *                         property="token",
    *                         description="Password reset token",
    *                         type="string",
    *                     ),
    *                 )
    *             )
    *         )
    * )
    */
    public function resetPassword(Request $request)
    {
        // Validate input data
        $validator = Validator::make($request->all(), [
            'email' => 'required|string|email',
            'password' => 'required|string',
            'password_confirmation' => 'required|string|same:password',
            'token' => 'required|string'
        ]);
        if ($validator->fails()) {
            return response()->json(
            [
                'error' =>
                        [
                            'code' => Enums\ErrorEnum::GENR0002,
                            'message' => Enums\ErrorEnum::getDescription(Enums\ErrorEnum::GENR0002)
                        ],
                'validation' => $validator->errors()
            ],
            Response::HTTP_UNPROCESSABLE_ENTITY);
        }

        $passwordReset = Models\PasswordReset::firstWhere([
            ['token', $request->token],
            ['email', $request->email]
        ]);
        if (!$passwordReset) {
            return response()->json(
                ['error' =>
                            [
                                'code' => Enums\ErrorEnum::AUTH0006,
                                'message' => Enums\ErrorEnum::getDescription(Enums\ErrorEnum::AUTH0006)
                            ]
                ], Response::HTTP_BAD_REQUEST
            );
        }

        $user = Models\User::firstWhere('email', $passwordReset->email);
        if (!$user) {
            return response()->json(
                ['error' =>
                            [
                                'code' => Enums\ErrorEnum::AUTH0003,
                                'message' => Enums\ErrorEnum::getDescription(Enums\ErrorEnum::AUTH0003)
                            ]
                ], Response::HTTP_BAD_REQUEST
            );
        }

        // Save new password
        $user->password = bcrypt($request->password);
        $user->save();
        // Delete password reset token
        $passwordReset->delete();
        // Send notification email
        $user->notify(new Notifications\PasswordResetSuccessNotification($passwordReset));

        return response()->json(['user' => $user], Response::HTTP_OK);
    }

    /**
    * @OA\Patch(
    *         path="/api/auth/password/change",
    *         tags={"Auth"},
    *         summary="Change password",
    *         description="Change an user's password (requires current password) and send notification mail",
    *         operationId="changePassword",
    *         @OA\Response(
    *             response=200,
    *             description="Successful operation"
    *         ),
    *         @OA\Response(
    *             response=422,
    *             description="Validation error"
    *         ),
    *         @OA\Response(
    *             response=403,
    *             description="Wrong combination of email and password or email not verified"
    *         ),
    *         @OA\Response(
    *             response=500,
    *             description="Server error"
    *         ),
    *         @OA\RequestBody(
    *             required=true,
    *             @OA\MediaType(
    *                 mediaType="application/x-www-form-urlencoded",
    *                 @OA\Schema(
    *                     type="object",
    *                      @OA\Property(
    *                         property="password",
    *                         description="Password",
    *                         type="string",
    *                         format="password"
    *                     ),
    *                     @OA\Property(
    *                         property="new_password",
    *                         description="New password",
    *                         type="string",
    *                         format="password"
    *                     ),
    *                     @OA\Property(
    *                         property="new_password_confirmation",
    *                         description="Confirm new password",
    *                         type="string",
    *                         format="password"
    *                     ),
    *                 )
    *             )
    *         )
    * )
    */
    public function changePassword(Request $request)
    {
        $user = $request->user();

        $email = $user->email;
        // Validate input data
        $validator = Validator::make($request->all(), [
            'password' => 'required|string',
            'new_password' => 'required|string|confirmed'
        ]);
        if ($validator->fails()) {
            return response()->json(
            [
                'error' =>
                        [
                            'code' => Enums\ErrorEnum::GENR0002,
                            'message' => Enums\ErrorEnum::getDescription(Enums\ErrorEnum::GENR0002)
                        ],
                'validation' => $validator->errors()
            ],
            Response::HTTP_UNPROCESSABLE_ENTITY);
        }

        // Check if the combination of email and password is correct, if it is then proceed, if no, throw error
        $credentials = request(['password']);
        $credentials['email'] = $email;
        $credentials['status'] = Enums\UserStatusEnum::Active;
        $credentials['deleted_at'] = null;

        // Check the combination of email and password, also check for activation status
        if(!Auth::guard('web')->attempt($credentials)) {
            return response()->json(
                ['error' =>
                            [
                                'code' => Enums\ErrorEnum::AUTH0001,
                                'message' => Enums\ErrorEnum::getDescription(Enums\ErrorEnum::AUTH0001)
                            ]
                ], Response::HTTP_UNAUTHORIZED
            );
        }

        // Save new password
        $user->password = bcrypt($request->new_password);
        $user->save();

        // Send notification email
        $user->notify(new Notifications\PasswordChangeSuccessNotification());

        return response()->json(['user' => $user], Response::HTTP_OK);
    }

    /**
    * @OA\Get(
    *         path="/api/auth/roles_permissions",
    *         tags={"Auth"},
    *         summary="Get all roles and permissions",
    *         description="Get all roles and permissions",
    *         @OA\Response(
    *             response=200,
    *             description="Successful operation"
    *         ),
    *         @OA\Response(
    *             response=500,
    *             description="Server error"
    *         ),
    * )
    */
    public function getRolesAndPermissions(Request $request) {
        // Authorization check
        $user = $request->user();
        if (!$user->hasPermissionTo(Enums\PermissionEnum::VIEW_ROLES_PERMISSIONS)) {

            return $this->returnUnauthorizedResponse();
        }

        $roles = SpatiePermissionModels\Role::get();
        $permissions = SpatiePermissionModels\Permission::get();

        return response()->json(['roles' => $roles, 'permissions' => $permissions], Response::HTTP_OK);
    }

    /**
    * @OA\Get(
    *         path="/api/auth/roles_w_permissions",
    *         tags={"Auth"},
    *         summary="Get all roles with associated permissions",
    *         description="Get all roles with associated permissions",
    *         @OA\Response(
    *             response=200,
    *             description="Successful operation"
    *         ),
    *         @OA\Response(
    *             response=500,
    *             description="Server error"
    *         ),
    * )
    */
    public function getRolesWithPermissions(Request $request) {
        // Authorization check
        $user = $request->user();
        if (!$user->hasPermissionTo(Enums\PermissionEnum::VIEW_ROLES_PERMISSIONS)) {

            return $this->returnUnauthorizedResponse();
        }

        $roles = SpatiePermissionModels\Role::with('permissions')->get();

        return response()->json(['roles' => $roles], Response::HTTP_OK);
    }

    /**
    * @OA\Post(
    *         path="/api/auth/roles",
    *         tags={"Auth"},
    *         summary="Create role",
    *         description="Create a new role",
    *         operationId="create-role",
    *         @OA\Response(
    *             response=200,
    *             description="Successful operation"
    *         ),
    *         @OA\Response(
    *             response=422,
    *             description="Validation error"
    *         ),
    *         @OA\Response(
    *             response=500,
    *             description="Server error"
    *         ),
    *         @OA\RequestBody(
    *             required=true,
    *             @OA\MediaType(
    *                 mediaType="application/x-www-form-urlencoded",
    *                 @OA\Schema(
    *                     type="object",
    *                      @OA\Property(
    *                         property="role_name",
    *                         description="Role name",
    *                         type="string",
    *                     ),
    *                 )
    *             )
    *         )
    * )
    */
    public function createRole(Request $request)
    {
        // Authorization check
        $user = $request->user();
        if (!$user->hasPermissionTo(Enums\PermissionEnum::CREATE_ROLES)) {

            return $this->returnUnauthorizedResponse();
        }

        // Validate input data
        $validator = Validator::make($request->all(), [
            'role_name' => 'required|string|unique:roles,name',
        ]);

        if ($validator->fails()) {

            return response()->json(
            [
                'error' =>
                        [
                            'code' => Enums\ErrorEnum::GENR0002,
                            'message' => Enums\ErrorEnum::getDescription(Enums\ErrorEnum::GENR0002)
                        ],
                'validation' => $validator->errors()
            ],
            Response::HTTP_UNPROCESSABLE_ENTITY);
        }

        // Create the role
        $role = SpatiePermissionModels\Role::create(['name' => $request->input('role_name')]);

        return response()->json(['role' => $role], Response::HTTP_OK);
    }

    /**
    * @OA\Delete(
    *         path="/api/auth/roles/{id}",
    *         tags={"Auth"},
    *         summary="Delete a role",
    *         description="Delete a role",
    *         operationId="delete-role",
    *         @OA\Response(
    *             response=204,
    *             description="Successful operation with no content in return"
    *         ),
    *         @OA\Response(
    *             response=500,
    *             description="Server error"
    *         ),
    *         @OA\Parameter(
    *             name="id",
    *             in="path",
    *             description="Role ID",
    *             required=true,
    *             @OA\Schema(
    *                 type="integer",
    *             )
    *         ),
    * )
    */
    public function deleteRole(Request $request, $id)
    {
        // Authorization check
        $user = $request->user();
        if (!$user->hasPermissionTo(Enums\PermissionEnum::DELETE_ROLES)) {

            return $this->returnUnauthorizedResponse();
        }

        // Check for data validity
        $role = SpatiePermissionModels\Role::find($id);
        if (!$id || empty($role)) {
            return response()->json(
                ['error' =>
                            [
                                'code' => Enums\ErrorEnum::AUTH0012,
                                'message' => Enums\ErrorEnum::getDescription(Enums\ErrorEnum::AUTH0012)
                            ]
                ], Response::HTTP_BAD_REQUEST
            );
        }
        // Delete the data
        $role->delete();

        return response()->json(null, Response::HTTP_NO_CONTENT);
    }

    /**
    * @OA\Put(
    *         path="/api/auth/update_roles_permissions_matrix",
    *         tags={"Auth"},
    *         summary="Update roles permissions matrix",
    *         description="Update roles permissions matrix",
    *         operationId="update-roles-permissions-matrix",
    *         @OA\Response(
    *             response=200,
    *             description="Successful operation"
    *         ),
    *         @OA\Response(
    *             response=422,
    *             description="Validation error"
    *         ),
    *         @OA\Response(
    *             response=500,
    *             description="Server error"
    *         ),
    *         @OA\RequestBody(
    *             required=true,
    *             @OA\MediaType(
    *                 mediaType="application/x-www-form-urlencoded",
    *                 @OA\Schema(
    *                     type="object",
    *                      @OA\Property(
    *                         property="matrix",
    *                         description="JSON of matrix object {'<role-1>':['<permission-name-1>','<permissions-name-2>'], '<role-2>':['<permission-name-1']}. Please replace single quotes with double quotes.",
    *                         type="string",
    *                     ),
    *                 )
    *             )
    *         )
    * )
    */
    public function updateRolesPermissionsMatrix(Request $request)
    {
        // Authorization check
        $user = $request->user();
        if (!$user->hasPermissionTo(Enums\PermissionEnum::UPDATE_PERMISSIONS)) {

            return $this->returnUnauthorizedResponse();
        }

        // Validate input data
        $validator = Validator::make($request->all(), [
            'matrix' => 'required',
        ]);

        if ($validator->fails()) {

            return response()->json(
            [
                'error' =>
                        [
                            'code' => Enums\ErrorEnum::GENR0002,
                            'message' => Enums\ErrorEnum::getDescription(Enums\ErrorEnum::GENR0002)
                        ],
                'validation' => $validator->errors()
            ],
            Response::HTTP_UNPROCESSABLE_ENTITY);
        }

        // Decode the json received from client
        $matrix = json_decode($request->input('matrix'));

        // Update permissions
        $roles = SpatiePermissionModels\Role::get();
        $permissions = SpatiePermissionModels\Permission::get();
        DB::beginTransaction();
        try {
            foreach ($matrix as $roleName => $associatedPermissions) {
                foreach ($roles as $role) {
                    // Find the role
                    if ($role->name == $roleName) {
                        // Apply permissions changed on that role
                        $role->syncPermissions($associatedPermissions);
                        break;
                    }
                }
            }

            DB::commit();
        } catch (\Exception $e) {
            DB::rollBack();

            return response()->json(
                ['error' =>
                            [
                                'code' => Enums\ErrorEnum::AUTH0013,
                                'message' => Enums\ErrorEnum::getDescription(Enums\ErrorEnum::AUTH0013)
                            ]
                ], Response::HTTP_BAD_REQUEST
            );
        }

        // Return roles with permissions after the update
        return $this->getRolesWithPermissions($request);
    }

    /*
    *   Return json with token inside
    **/
    protected function respondWithToken($token)
    {
        return [
            'token' => $token,
            'token_type'   => 'bearer',
            'expires_in' => auth('api')->factory()->getTTL() * 60
        ];
    }
}
