<?php

namespace App\Http\Controllers;

use App\Http\Controllers\BaseController as BaseController;
use App\Http\Resources\User as UserResource;
use App\Services\UserServiceInterface;
use Illuminate\Http\Request;

/**
 * @OA\Schema(
 *     schema="UserResource",
 *     type="object",
 *     title="User Resource",
 *     required={"name", "email", "password", "company_id"},
 *     properties={
 *         @OA\Property(property="id", type="integer", example=1),
 *         @OA\Property(property="name", type="string", example="BoJack Horseman"),
 *         @OA\Property(property="email", type="string", example="bojack@horse.men"),
 *         @OA\Property(property="password", type="string", example="admin@adm"),
 *     }
 * )
 *
 * @OA\Schema(
 *     schema="UserStoreRequest",
 *     type="object",
 *     title="User Store Request",
 *     required={"name", "email", "password", "company_id"},
 *     properties={
 *         @OA\Property(property="name", type="string", example="BoJack Horseman"),
 *         @OA\Property(property="email", type="string", example="bojack@horse.men"),
 *         @OA\Property(property="password", type="string", example="admin@adm"),
 *     }
 * ) 
 * 
 */
class UserController extends BaseController
{
    protected $userService;

    public function __construct(UserServiceInterface $userService)
    {
        $this->middleware('admin');
        $this->userService = $userService;
    }

    /**
     * @OA\Get(
     *     path="/api/users",
     *     summary="Get list of users",
     *     tags={"Users"},
     *     security={{"bearerAuth": {}}},
     *     @OA\Response(
     *         response=200,
     *         description="Successful operation",
     *         @OA\JsonContent(type="array", @OA\Items(ref="#/components/schemas/User"))
     *     ),
     *     @OA\Response(
     *         response=401,
     *         description="Unauthorized",
     *     )
     * )
     */
    public function index()
    {
        $users = $this->userService->getAllUsers();
        return $this->sendResponse(UserResource::collection($users), 'Users retrieved successfully.');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        
    }

    /**
     * @OA\Post(
     *     path="/api/users",
     *     summary="Create a new user",
     *     tags={"Users"},
     *     security={{"bearerAuth": {}}},
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(ref="#/components/schemas/UserStoreRequest")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Successful operation",
     *         @OA\JsonContent(ref="#/components/schemas/User")
     *     ),
     *     @OA\Response(
     *         response=422,
     *         description="Validation error"
     *     ),
     *     @OA\Response(
     *         response=401,
     *         description="Unauthorized",
     *     )
     * )
     */
    public function store(Request $request)
    {
        $data = $request->all();
        try {
            $user = $this->userService->createUser($data);
            return $this->sendResponse(new UserResource($user), 'User created successfully.');
        } catch (\Exception $e) {
            return $this->sendError($e->getMessage());
        }
    }

    /**
     * @OA\Get(
     *     path="/api/users/{id}",
     *     summary="Get details of a specific user",
     *     tags={"Users"},
     *     security={{"bearerAuth": {}}},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         description="ID of the user to retrieve",
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="User successfully retrieved",
     *         @OA\JsonContent(
     *             ref="#/components/schemas/UserResource"
     *         )
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="User not found"
     *     ),
     *     @OA\Response(
     *         response=401,
     *         description="Unauthorized"
     *     )
     * )
     */
    public function show($id)
    {
        $user = $this->userService->getUserById($id);
        if (!$user) {
            return $this->sendError('User not found.', [], 404);
        }
        return $this->sendResponse(new UserResource($user), 'User retrieved successfully.');
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * @OA\Put(
     *     path="/api/users/{id}",
     *     summary="Update an existing user",
     *     tags={"Users"},
     *     security={{"bearerAuth": {}}},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         description="ID of the user to update",
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(ref="#/components/schemas/UserStoreRequest")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Successful operation",
     *         @OA\JsonContent(ref="#/components/schemas/User")
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="User not found"
     *     ),
     *     @OA\Response(
     *         response=422,
     *         description="Validation error"
     *     ),
     *     @OA\Response(
     *         response=401,
     *         description="Unauthorized",
     *     )
     * )
     */
    public function update(Request $request, $id)
    {
        $data = $request->all();
        try {
            $user = $this->userService->updateUser($id, $data);
            if (!$user) {
                return $this->sendError('User not found.', [], 404);
            }
            return $this->sendResponse(new UserResource($user), 'User updated successfully.');
        } catch (\Exception $e) {
            return $this->sendError($e->getMessage());
        }
    }

    /**
     * @OA\Put(
     *     path="/api/users/{id}/restore",
     *     summary="Restore a soft-deleted user and his data",
     *     tags={"Users"},
     *     security={{"bearerAuth": {}}},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         description="ID of the user to restore",
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="User restored successfully",
     *         @OA\JsonContent(
     *             @OA\Property(property="success", type="boolean", example=true),
     *             @OA\Property(property="message", type="string", example="User, Wallet, and Tasks restored successfully.")
     *         )
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="User not found"
     *     ),
     *     @OA\Response(
     *         response=400,
     *         description="Error restoring user"
     *     ),
     *     @OA\Response(
     *         response=401,
     *         description="Unauthorized"
     *     )
     * )
     */
    public function restore($id)
    {
        try {
            $user = $this->userService->getUserById($id);
            if (!$user) {
                return $this->sendError('User not found.', [], 404);
            }
            if ($user->admin) {
                return $this->sendError('Validation Error.', ['User Admin cannot be restored.']);
            }
            $this->userService->restoreUser($id);
            return $this->sendResponse(new UserResource($user), 'User restored successfully.');
        } catch (\Exception $e) {
            return $this->sendError($e->getMessage());
        }
    }

    /**
     * @OA\Delete(
     *     path="/api/users/{id}",
     *     summary="Delete a user",
     *     tags={"Users"},
     *     security={{"bearerAuth": {}}},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         description="ID of the user to delete",
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Response(
     *         response=204,
     *         description="User deleted successfully"
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="User not found"
     *     ),
     *     @OA\Response(
     *         response=401,
     *         description="Unauthorized",
     *     )
     * )
     */
    public function destroy($id)
    {
        try {
            $user = $this->userService->getUserById($id);
            if (!$user) {
                return $this->sendError('User not found.', [], 404);
            }
            if ($user->admin) {
                return $this->sendError('Validation Error.', ['User Admin cannot be deleted.']);
            }
            $this->userService->deleteUser($id);
            return $this->sendResponse([], 'User and related data deleted successfully.');
        } catch (\Exception $e) {
            return $this->sendError($e->getMessage());
        }
    }
}