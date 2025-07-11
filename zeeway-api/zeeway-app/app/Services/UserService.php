<?php

namespace App\Services;

use App\Repositories\UserRepositoryInterface;
use Illuminate\Support\Facades\Validator;

class UserService implements UserServiceInterface
{
    protected $userRepo;

    public function __construct(UserRepositoryInterface $userRepo)
    {
        $this->userRepo = $userRepo;
    }

    public function getAllUsers()
    {
        return $this->userRepo->getAll();
    }

    public function getUserById($id)
    {
        return $this->userRepo->findById($id);
    }

    public function createUser(array $data)
    {
        $validator = Validator::make($data, [
            'name' => 'required|string|max:150',
            'email' => 'required|email|max:150',
            'password' => [
                'required',
                'string',
                'min:8',
                'max:20',
                'regex:/[A-Z]/',
                'regex:/[0-9]/',
                'regex:/[@$!%*?&]/'
            ],
        ]);
        if ($validator->fails()) {
            throw new \Exception('Validation Error');
        }

        $data['password'] = bcrypt($data['password']);
        return $this->userRepo->create($data);
    }

    public function updateUser($id, array $data)
    {
        $validator = Validator::make($data, [
            'name' => 'required|string|max:150',
            'email' => 'required|email|max:150',
            'password' => [
                'required',
                'string',
                'min:8',
                'max:20',
                'regex:/[A-Z]/',
                'regex:/[0-9]/',
                'regex:/[@$!%*?&]/'
            ],
        ]);
        if ($validator->fails()) {
            throw new \Exception('Validation Error');
        }

        $data['password'] = bcrypt($data['password']);
        return $this->userRepo->update($id, $data);
    }

    public function deleteUser($id)
    {
        $user = $this->userRepo->findById($id);
        if (!$user) {
            throw new \Exception('User not found');
        }

        $authUser = auth()->user();
        if (!$authUser->admin && $authUser->id !== $user->id) {
            throw new \Exception('Unauthorized - User not admin');
        }

        return $this->userRepo->delete($id);
    }

    public function restoreUser($id)
    {
        $user = $this->userRepo->findTrashedById($id);
        if (!$user) {
            throw new \Exception('User not found');
        }

        return $this->userRepo->restore($id);
    }
}