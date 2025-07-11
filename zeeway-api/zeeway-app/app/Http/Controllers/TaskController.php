<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Controllers\BaseController as BaseController;
use App\Http\Resources\Task as TaskResource;
use App\Services\TaskServiceInterface;

/**
 * @OA\Schema(
 *     schema="TaskResource",
 *     type="object",
 *     title="Task Resource",
 *     properties={
 *         @OA\Property(property="id", type="string", format="uuid", example="550e8400-e29b-41d4-a716-446655440000"),
 *         @OA\Property(property="user_id", type="string", example="550e8400-e29b-41d4-a716-446655440000"),
 *         @OA\Property(property="title", type="string", example="Nova Tarefa"),
 *         @OA\Property(property="description", type="string", example="Detalhes sobre a tarefa", nullable=true),
 *         @OA\Property(property="status", type="string", enum={"pending", "in_progress", "done"}, example="pending"),
 *         @OA\Property(property="due_date", type="string", format="date", example="2025-12-31", nullable=true),
 *         @OA\Property(property="created_at", type="string", format="date-time", example="2025-07-11T12:00:00Z"),
 *         @OA\Property(property="updated_at", type="string", format="date-time", example="2025-07-11T12:00:00Z"),
 *     }
 * )
 *
 * @OA\Schema(
 *     schema="TaskStoreRequest",
 *     type="object",
 *     title="Task Store Request",
 *     properties={
 *         @OA\Property(property="user_id", type="string"),
 *         @OA\Property(property="title", type="string"),
 *         @OA\Property(property="description", type="string"),
 *         @OA\Property(property="status", type="string"),
 *         @OA\Property(property="due_date", type="date"),
 *     },
 *     required={"user_id", "title", "status"}
 * )
 */
class TaskController extends BaseController
{
    protected $taskService;

    public function __construct(TaskServiceInterface $taskService)
    {
        $this->middleware('admin', ['except' => [
            'tasksLoggedUser', 'storeLoggedUser', 'revert'
        ]]);
        $this->taskService = $taskService;
    }

    /**
     * @OA\Get(
     *     path="/api/tasks",
     *     summary="Get list of tasks",
     *     tags={"Tasks"},
     *     security={{"bearerAuth": {}}},
     *     @OA\Response(
     *         response=200,
     *         description="Successful operation",
     *         @OA\JsonContent(type="array", @OA\Items(ref="#/components/schemas/TaskResource"))
     *     ),
     *     @OA\Response(
     *         response=401,
     *         description="Unauthorized"
     *     )
     * )
     */
    public function index()
    {
        $tasks = $this->taskService->getAllTasks();
        return $this->sendResponse(TaskResource::collection($tasks), 'Tasks retrieved successfully.');
    }

    /**
     * @OA\Get(
     *     path="/api/tasks/user/{id}",
     *     summary="Get tasks of a specific user",
     *     tags={"Tasks"},
     *     security={{"bearerAuth": {}}},
     *     @OA\Parameter(
     *         name="user_id",
     *         in="query",
     *         required=true,
     *         description="ID do usuário para obter as transações",
     *         @OA\Schema(type="string")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Successful operation",
     *         @OA\JsonContent(
     *             type="array",
     *             @OA\Items(ref="#/components/schemas/TaskResource")
     *         )
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Usuário não encontrado",
     *     ),
     *     @OA\Response(
     *         response=401,
     *         description="Unauthorized"
     *     )
     * )
     */
    public function tasksByUser($userId)
    {
        if (!\App\Models\User::where('id', $userId)->exists()) {
            return $this->sendError('User not found.', [], 404);
        }
    
        $tasks = $this->taskService->getTasksByUserId($userId);
        return $this->sendResponse(TaskResource::collection($tasks), 'Tasks retrieved successfully.');
    }

    /**
     * @OA\Get(
     *     path="/api/tasks/user/me",
     *     summary="Get tasks of the logged-in user",
     *     tags={"Tasks"},
     *     security={{"bearerAuth": {}}},
     *     @OA\Response(
     *         response=200,
     *         description="Successful operation",
     *         @OA\JsonContent(
     *             type="array",
     *             @OA\Items(ref="#/components/schemas/TaskResource")
     *         )
     *     ),
     *     @OA\Response(
     *         response=401,
     *         description="Unauthorized"
     *     )
     * )
     */
    public function tasksLoggedUser(Request $request)
    {
        $user = auth()->user();

        if (!$user) {
            return $this->sendError('Unauthorized', [], 401);
        }

        $tasks = $this->taskService->getTasksByUserId($user->id);
        return $this->sendResponse(TaskResource::collection($tasks), 'Your tasks retrieved successfully.');
    }
   
    /**
     * @OA\Post(
     *     path="/api/tasks",
     *     summary="Create a new task",
     *     tags={"Tasks"},
     *     security={{"bearerAuth": {}}},
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(ref="#/components/schemas/TaskStoreRequest")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Task created successfully",
     *         @OA\JsonContent(ref="#/components/schemas/TaskResource")
     *     ),
     *     @OA\Response(
     *         response=422,
     *         description="Validation error"
     *     ),
     *     @OA\Response(
     *         response=401,
     *         description="Unauthorized"
     *     )
     * )
     */
    public function store(Request $request)
    {
        try {
            $data = $request->all();
            $task = $this->taskService->createTask($data);
            return $this->sendResponse(new TaskResource($task), 'Task created successfully.');
        } catch (\Exception $e) {
            return $this->sendError($e->getMessage());
        }
    }

    /**
     * @OA\Post(
     *     path="/api/tasks/user/me",
     *     summary="Create a new task for logged-in user",
     *     tags={"Tasks"},
     *     security={{"bearerAuth": {}}},
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             type="object",
     *             properties={
     *                 @OA\Property(property="title", type="string", example="teste"),
     *                 @OA\Property(property="status", type="string", example="done"),
     *                 @OA\Property(property="description", type="string", example="teste teste")
     *             },
     *             required={"title", "status"}
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Task created successfully",
     *         @OA\JsonContent(ref="#/components/schemas/TaskResource")
     *     ),
     *     @OA\Response(
     *         response=401,
     *         description="Unauthorized"
     *     ),
     *     @OA\Response(
     *         response=422,
     *         description="Validation error"
     *     )
     * )
     */
    public function storeLoggedUser(Request $request)
    {
        try {
            $user = auth()->user();
            if (!$user) {
                return $this->sendError('Unauthorized', [], 401);
            }

            $data = $request->all();
            $data['user_id'] = $user->id;

            $task = $this->taskService->createTask($data);

            return $this->sendResponse(new TaskResource($task), 'Task created successfully.');
        } catch (\Exception $e) {
            return $this->sendError($e->getMessage());
        }
    }

    /**
     * @OA\Put(
     *     path="/api/tasks/{id}",
     *     summary="Update an existing task",
     *     tags={"Tasks"},
     *     security={{"bearerAuth": {}}},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         description="ID of the task to update",
     *         required=true,
     *         @OA\Schema(
     *             type="string"
     *         )
     *     ),
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(ref="#/components/schemas/TaskStoreRequest")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Task updated successfully",
     *         @OA\JsonContent(ref="#/components/schemas/TaskResource")
     *     ),
     *     @OA\Response(
     *         response=422,
     *         description="Validation error"
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Task not found"
     *     ),
     *     @OA\Response(
     *         response=401,
     *         description="Unauthorized"
     *     )
     * )
     */
    public function update(Request $request, $id)
    {
        try {
            $data = $request->all();
            $task = $this->taskService->updateTask($id, $data);
            return $this->sendResponse(new TaskResource($task), 'Task updated successfully.');
        } catch (\Exception $e) {
            if ($e instanceof \Illuminate\Validation\ValidationException) {
                return $this->sendError($e->errors(), 'Validation error', 422);
            } elseif ($e->getMessage() === 'Task not found') {
                return $this->sendError('Task not found', 'Error', 404);
            }
            return $this->sendError($e->getMessage(), 'Error');
        }
    }

    /**
     * @OA\Delete(
     *     path="/api/tasks/{id}",
     *     summary="Delete a task",
     *     tags={"Tasks"},
     *     security={{"bearerAuth": {}}},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         description="ID of the task to delete",
     *         @OA\Schema(type="string")
     *     ),
     *     @OA\Response(
     *         response=204,
     *         description="Task deleted successfully"
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Task not found"
     *     ),
     *     @OA\Response(
     *         response=401,
     *         description="Unauthorized"
     *     )
     * )
     */
    public function destroy($id)
    {
        try {
            $task = $this->taskService->getTaskById($id);
            if (!$task) {
                return $this->sendError('Task not found.', [], 404);
            }

            $this->taskService->deleteTask($id);
            return $this->sendResponse([], 'Task deleted successfully.');
        } catch (\Exception $e) {
            return $this->sendError($e->getMessage());
        }
    }
}