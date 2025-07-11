<?php

namespace App\Repositories;

interface TaskRepositoryInterface
{
    public function getAll();
    public function findByUserId($userId);
    public function findById($id);
    public function create(array $data);
    public function update($id, array $data);
    public function delete($id);
    public function findTrashedById($id);
}