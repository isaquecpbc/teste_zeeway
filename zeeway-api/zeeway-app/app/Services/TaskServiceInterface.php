<?php

namespace App\Services;

interface TaskServiceInterface
{
    public function getAllTasks();
    public function getTasksByUserId($userId);
    public function getTaskById($id);
    public function createTask(array $data);
    public function updateTask($taskId, array $data);
    public function deleteTask($id);
}