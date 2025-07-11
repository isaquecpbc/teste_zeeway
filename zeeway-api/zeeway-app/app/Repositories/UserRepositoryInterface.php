<?php

namespace App\Repositories;

use App\Models\User;

interface UserRepositoryInterface
{
    public function getAll();
    public function findById($id);
    public function create(array $data);
    public function update($id, array $data);
    public function delete($id);
    public function restore($id);
    public function findTrashedById($id);
}