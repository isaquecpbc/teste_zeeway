<?php

namespace App\Repositories;

use App\Models\User;

class UserRepository implements UserRepositoryInterface
{
    public function getAll()
    {
        return User::withTrashed()->get();
    }

    public function findById($id)
    {
        return User::withTrashed()->find($id);
    }

    public function create(array $data)
    {
        return User::create($data);
    }

    public function update($id, array $data)
    {
        $user = $this->findById($id);
        if ($user) {
            $user->update($data);
        }
        return $user;
    }

    public function delete($id)
    {
        $user = $this->findById($id);
        if ($user) {
            $user->delete();
        }
    }

    public function restore($id)
    {
        $user = User::withTrashed()->find($id);
        
        if ($user) {
            $user->restore();
        }
        return $user;
    }

    public function findTrashedById($id)
    {
        return User::withTrashed()->find($id);
    }
}