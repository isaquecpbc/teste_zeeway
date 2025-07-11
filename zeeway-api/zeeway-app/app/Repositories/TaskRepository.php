<?php

namespace App\Repositories;

use App\Models\Task;

class TaskRepository implements TaskRepositoryInterface
{
    public function getAll()
    {
        return Task::withTrashed()->get();
    }

    public function findByUserId($userId)
    {
        return Task::withTrashed()->where('user_id', $userId)->get();
    }

    public function findById($id)
    {
        return Task::withTrashed()->find($id);
    }

    public function create(array $data)
    {
        return Task::create($data);
    }

    public function update($id, array $data)
    {
        return Task::update($data);
    }

    public function delete($id)
    {
        $task = $this->findById($id);
        if ($task) {
            $task->delete();
        }
    }

    public function findTrashedById($id)
    {
        return Task::withTrashed()->find($id);
    }
}