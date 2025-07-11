<?php

namespace App\Services;

use App\Repositories\TaskRepositoryInterface;
use Illuminate\Support\Facades\Validator;

class TaskService implements TaskServiceInterface
{
    protected $taskRepo;
    protected $walletService;

    public function __construct(
        TaskRepositoryInterface $taskRepo
    ) {
        $this->taskRepo = $taskRepo;
    }

    public function getAllTasks()
    {
        return $this->taskRepo->getAll();
    }

    public function getTasksByUserId($userId)
    {
        return $this->taskRepo->findByUserId($userId);
    }

    public function getTaskById($id)
    {
        return $this->taskRepo->findById($id);
    }

    public function createTask(array $data)
    {
        $validator = Validator::make($data, [
            'user_id' => 'required|exists:users,id',
            'title' => 'required|string|min:3',
            'description' => 'nullable|string',
            'status' => 'required|string|in:pending,in_progress,done',
            'due_date' => 'nullable|date',
        ]);
        if ($validator->fails()) {
            throw new \Exception('Validation Error');
        }

        return $this->taskRepo->create($data);
    }

    public function updateTask($taskId, array $data)
    {
        $validator = Validator::make($data, [
            'user_id' => 'sometimes|required|exists:users,id',
            'title' => 'sometimes|required|string|min:3',
            'description' => 'nullable|string',
            'status' => 'sometimes|required|string|in:pending,in_progress,done',
            'due_date' => 'nullable|date',
        ]);

        if ($validator->fails()) {
            throw new \Illuminate\Validation\ValidationException($validator);
        }

        $task = $this->taskRepo->find($taskId);
        if (!$task) {
            throw new \Exception('Task not found');
        }

        return $this->taskRepo->update($taskId, $data);
    }

    public function deleteTask($id)
    {
        $task = $this->taskRepo->findById($id);
        if (!$task) {
            throw new \Exception('Task not found');
        }

        $user = auth()->user();
        if (!$user->admin && $task->user_id !== $user->id) {
            throw new \Exception('Unauthorized - Task does not belong to the user');
        }
        
        return $this->taskRepo->delete($id);
    }
}