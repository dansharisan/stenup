<?php

namespace App\Http\Controllers;

use Validator;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Symfony\Component\HttpFoundation\Response as Response;
use App\Http\Traits as Traits;
use Spatie\Permission\Models as SpatiePermissionModels;
use App\Enums as Enums;
use App\Models as Models;

class UserController extends Controller
{
    use Traits\ResponseTrait;
    /**
    * @OA\Get(
    *         path="/api/users",
    *         tags={"User"},
    *         summary="Get users",
    *         description="Get list of users",
    *         operationId="user-list",
    *         @OA\Parameter(
    *             name="page",
    *             in="query",
    *             description="Current page",
    *             required=false,
    *             @OA\Schema(
    *                 type="integer",
    *             )
    *         ),
    *         @OA\Parameter(
    *             name="per_page",
    *             in="query",
    *             description="Items per page",
    *             required=false,
    *             @OA\Schema(
    *                 type="integer",
    *             )
    *         ),
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
    public function index(Request $request)
    {
        // Authorization check
        $user = $request->user();
        if (!$user->hasPermissionTo(Enums\PermissionEnum::VIEW_USERS)) {

            return $this->returnUnauthorizedResponse();
        }

        $users = Models\User::orderBy('created_at', 'desc')->paginate($request->query('per_page'));

        for ($i=0; $i<count($users); $i++) {
            $roleArr = [];
            foreach ($users[$i]->getRoleNames() as $role) {
                array_push($roleArr, $role);
            }
            $users[$i]['display_roles'] = implode(", ", $roleArr);
            $users[$i]['status'] = Enums\UserStatusEnum::getKey($users[$i]['status']);
        }

        return response()->json(['users' => $users], Response::HTTP_OK);
    }

    /**
    * @OA\Patch(
    *         path="/api/users/{id}/ban",
    *         tags={"User"},
    *         summary="Ban an user",
    *         description="Ban an user",
    *         operationId="ban-user",
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
    *             description="User ID",
    *             required=true,
    *             @OA\Schema(
    *                 type="integer",
    *             )
    *         ),
    * )
    */
    public function ban(Request $request, $id)
    {
        // Authorization check
        $user = $request->user();
        if (!$user->hasPermissionTo(Enums\PermissionEnum::UPDATE_USERS)) {

            return $this->returnUnauthorizedResponse();
        }

        // Check for data validity
        $user = Models\User::find($id);
        if (!$id || empty($user)) {
            return response()->json(
                ['error' =>
                            [
                                'code' => Enums\ErrorEnum::USER0001,
                                'message' => Enums\ErrorEnum::getDescription(Enums\ErrorEnum::USER0001)
                            ]
                ], Response::HTTP_BAD_REQUEST
            );
        }
        // Update the data
        $user->status = Enums\UserStatusEnum::Banned;
        $user->save();

        return response()->json(null, Response::HTTP_NO_CONTENT);
    }

    /**
    * @OA\Patch(
    *         path="/api/users/{id}/unban",
    *         tags={"User"},
    *         summary="Unban an user",
    *         description="Unban an user",
    *         operationId="unban-user",
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
    *             description="User ID",
    *             required=true,
    *             @OA\Schema(
    *                 type="integer",
    *             )
    *         ),
    * )
    */
    public function unban(Request $request, $id)
    {
        // Authorization check
        $user = $request->user();
        if (!$user->hasPermissionTo(Enums\PermissionEnum::UPDATE_USERS)) {

            return $this->returnUnauthorizedResponse();
        }

        // Check for data validity
        $user = Models\User::find($id);
        if (!$id || empty($user)) {
            return response()->json(
                ['error' =>
                            [
                                'code' => Enums\ErrorEnum::USER0001,
                                'message' => Enums\ErrorEnum::getDescription(Enums\ErrorEnum::USER0001)
                            ]
                ], Response::HTTP_BAD_REQUEST
            );
        }
        // Update the data
        $user->status = Enums\UserStatusEnum::Active;
        $user->save();

        return response()->json(null, Response::HTTP_NO_CONTENT);
    }

    /**
    * @OA\Delete(
    *         path="/api/users/{id}",
    *         tags={"User"},
    *         summary="Delete an user",
    *         description="Delete an user",
    *         operationId="delete-user",
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
    *             description="User ID",
    *             required=true,
    *             @OA\Schema(
    *                 type="integer",
    *             )
    *         ),
    * )
    */
    public function delete(Request $request, $id)
    {
        // Authorization check
        $user = $request->user();
        if (!$user->hasPermissionTo(Enums\PermissionEnum::DELETE_USERS)) {

            return $this->returnUnauthorizedResponse();
        }

        // Check for data validity
        $user = Models\User::find($id);
        if (!$id || empty($user)) {
            return response()->json(
                ['error' =>
                            [
                                'code' => Enums\ErrorEnum::USER0001,
                                'message' => Enums\ErrorEnum::getDescription(Enums\ErrorEnum::USER0001)
                            ]
                ], Response::HTTP_BAD_REQUEST
            );
        }
        // Delete the data
        $user->delete();

        return response()->json(null, Response::HTTP_NO_CONTENT);
    }

    /**
    * @OA\Post(
    *         path="/api/users/collection:batchDelete",
    *         tags={"User"},
    *         summary="Delete selected users",
    *         description="Delete selected users",
    *         operationId="delete-user-batch",
    *         @OA\Response(
    *             response=204,
    *             description="Successful operation with no content in return"
    *         ),
    *         @OA\Response(
    *             response=422,
    *             description="Invalid input"
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
    *                         property="ids",
    *                         description="Users' IDs",
    *                         type="array",
    *                         @OA\Items(
    *                             type="integer"
    *                         ),
    *                     ),
    *                 )
    *             )
    *         )
    * )
    */
    public function batchDelete(Request $request)
    {
        // Authorization check
        $user = $request->user();
        if (!$user->hasPermissionTo(Enums\PermissionEnum::DELETE_USERS)) {

            return $this->returnUnauthorizedResponse();
        }

        // Check for data validity
        $ids = $request->input('ids');
        $idArr = explode(',', $ids);
        if (empty($ids) || !is_array($idArr) || count($idArr) == 0 || $idArr != array_filter($idArr, 'is_numeric')) {
            return response()->json(
                ['error' =>
                            [
                                'code' => Enums\ErrorEnum::USER0002,
                                'message' => Enums\ErrorEnum::getDescription(Enums\ErrorEnum::USER0002)
                            ]
                ], Response::HTTP_BAD_REQUEST
            );
        }

        // Delete selected users
        Models\User::whereIn('id', $idArr)->delete();

        return response()->json(null, Response::HTTP_NO_CONTENT);
    }

    /**
    * @OA\Patch(
    *         path="/api/users/{id}",
    *         tags={"User"},
    *         summary="Edit an user",
    *         description="Edit an user",
    *         operationId="edit-user",
    *         @OA\Response(
    *             response=200,
    *             description="Successful operation"
    *         ),
    *         @OA\Response(
    *             response=500,
    *             description="Server error"
    *         ),
    *         @OA\Parameter(
    *             name="id",
    *             in="path",
    *             description="User ID",
    *             required=true,
    *             @OA\Schema(
    *                 type="integer",
    *             )
    *         ),
    *         @OA\RequestBody(
    *             required=true,
    *             @OA\MediaType(
    *                 mediaType="application/x-www-form-urlencoded",
    *                 @OA\Schema(
    *                     type="object",
    *                     @OA\Property(
    *                         property="email_verified_at",
    *                         description="Email verified date",
    *                         type="string",
    *                         format="date",
    *                     ),
    *                     @OA\Property(
    *                         property="role_ids",
    *                         description="Role IDs",
    *                         type="array",
    *                         @OA\Items(
    *                             type="integer"
    *                         ),
    *                     ),
    *                 )
    *             )
    *         )
    * )
    */
    public function update(Request $request, $id)
    {
        // Authorization check
        $user = $request->user();
        if (!$user->hasPermissionTo(Enums\PermissionEnum::UPDATE_USERS)) {

            return $this->returnUnauthorizedResponse();
        }

        $roleIds = $request->input('role_ids');
        $roleIdArr = explode(',', $roleIds);
        // Check for data validity
        if (empty($roleIds) || !is_array($roleIdArr) || count($roleIdArr) == 0 || $roleIdArr != array_filter($roleIdArr, 'is_numeric')) {
            return response()->json(
                ['error' =>
                            [
                                'code' => Enums\ErrorEnum::USER0003,
                                'message' => Enums\ErrorEnum::getDescription(Enums\ErrorEnum::USER0003)
                            ]
                ], Response::HTTP_BAD_REQUEST
            );
        }

        try {
            DB::beginTransaction();

            $user = Models\User::find($id);
            if ($user == null) {
                return response()->json(
                    ['error' =>
                                [
                                    'code' => Enums\ErrorEnum::USER0001,
                                    'message' => Enums\ErrorEnum::getDescription(Enums\ErrorEnum::USER0001)
                                ]
                    ], Response::HTTP_BAD_REQUEST
                );
            }
            // Update user data
            $user->fill($request->all());
            $verifiedAt = $request->input('email_verified_at');
            if ($verifiedAt) {
                $user->email_verified_at = date("Y-m-d H:i:s", strtotime($verifiedAt));
            }
            $user->save();

            // Remove old roles
            DB::table('model_has_roles')->where('model_id', $id)->where('model_type', User::class)->delete();
            // Add new roles
            $roleIdArr = preg_split('/,/', $roleIds, null, PREG_SPLIT_NO_EMPTY);
            if ($roleIdArr && is_array($roleIdArr) && !empty($roleIdArr[0]) && count($roleIdArr) > 0) {
                foreach ($roleIdArr as $roleId) {
                    $role = SpatiePermissionModels\Role::find($roleId);
                    $user->assignRole($role->name);
                }
            }

            DB::commit();
        } catch (\Exception $e) {
            DB::rollBack();

            return response()->json(
                ['error' =>
                            [
                                'code' => Enums\ErrorEnum::GENR0001,
                                'message' => $e->getMessage()
                            ]
                ], Response::HTTP_INTERNAL_SERVER_ERROR
            );
        }

        return response()->json(['user' => $user], Response::HTTP_OK);
    }

    /**
    * @OA\Post(
    *         path="/api/users",
    *         tags={"User"},
    *         summary="Create an user",
    *         description="Create an user",
    *         operationId="create-user",
    *         @OA\Response(
    *             response=200,
    *             description="Successful operation"
    *         ),
    *         @OA\Response(
    *             response=422,
    *             description="Invalid input or email taken"
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
    *                         property="email_verified_at",
    *                         description="Email verified date",
    *                         type="string",
    *                         format="date",
    *                     ),
    *                     @OA\Property(
    *                         property="role_ids",
    *                         description="Role IDs",
    *                         type="array",
    *                         @OA\Items(
    *                             type="integer"
    *                         ),
    *                     ),
    *                 )
    *             )
    *         )
    * )
    */
    public function store(Request $request)
    {
        // Authorization check
        $user = $request->user();
        if (!$user->hasPermissionTo(Enums\PermissionEnum::CREATE_USERS)) {

            return $this->returnUnauthorizedResponse();
        }

        $roleIds = $request->input('role_ids');
        $roleIdArr = explode(',', $roleIds);

        // Validate input data
        $validator = Validator::make($request->all(), [
            'email' => 'required|string|email|unique:users',
            'password' => 'required|string',
            'role_ids' => 'required',
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

        // Check for role Id array validity
        if (!is_array($roleIdArr) || count($roleIdArr) == 0 || $roleIdArr != array_filter($roleIdArr, 'is_numeric')) {
            return response()->json(
                ['error' =>
                            [
                                'code' => Enums\ErrorEnum::USER0003,
                                'message' => Enums\ErrorEnum::getDescription(Enums\ErrorEnum::USER0003)
                            ]
                ], Response::HTTP_BAD_REQUEST
            );
        }

        // Create user
        try {
            DB::beginTransaction();
            $user = new Models\User([
                'email' => $request->email,
                'password' => bcrypt($request->password),
            ]);

            $verifiedAt = $request->input('email_verified_at');
            if ($verifiedAt) {
                $user->email_verified_at = date("Y-m-d H:i:s", strtotime($verifiedAt));
            }

            $user->save();

            // Add new roles
            $roleIdArr = preg_split('/,/', $roleIds, null, PREG_SPLIT_NO_EMPTY);
            if ($roleIdArr && is_array($roleIdArr) && !empty($roleIdArr[0]) && count($roleIdArr) > 0) {
                foreach ($roleIdArr as $roleId) {
                    $role = SpatiePermissionModels\Role::find($roleId);
                    $user->assignRole($role->name);
                }
            }
            DB::commit();
        } catch (\Exception $e) {
            DB::rollBack();

            return response()->json(
                ['error' =>
                            [
                                'code' => Enums\ErrorEnum::GENR0001,
                                'message' => $e->getMessage()
                            ]
                ], Response::HTTP_INTERNAL_SERVER_ERROR
            );
        }

        return response()->json(['user' => $user], Response::HTTP_OK);
    }

    /**
    * @OA\Get(
    *         path="/api/users/registered_user_stats",
    *         tags={"User"},
    *         summary="Get registered user stats",
    *         description="Get registered user stats",
    *         operationId="registered-user-stats",
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
    public function registeredUserStats(Request $request)
    {
        // Authorization check
        $user = $request->user();
        if (!$user->hasPermissionTo(Enums\PermissionEnum::VIEW_DASHBOARD)) {

            return $this->returnUnauthorizedResponse();
        }

        $registeredUserStats = [];
        $last7Days = [];
        $last7DayStats = [];

        $registeredUserStats['total'] = Models\User::count();

        for ($i=0; $i<7; $i++)
        {
            array_push($last7Days, date("Y-m-d", strtotime($i." days ago")));
        }

        foreach ($last7Days as $eachDay) {
            $totalUsersOfDay = Models\User::whereBetween('created_at', [$eachDay . " 00:00:00", $eachDay . " 23:59:59"])->count();
            $last7DayStats[$eachDay] = $totalUsersOfDay;
        }
        $registeredUserStats['last_7_day_stats'] = array_reverse($last7DayStats);

        return response()->json(['user_stats' => $registeredUserStats], Response::HTTP_OK);
    }
}
