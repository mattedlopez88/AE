<?php

namespace App\Services;

use App\Repositories\UserRepository;
use Illuminate\Database\Eloquent\Collection;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;
use Illuminate\Support\Facades\DB; // For transactions

class UserService
{
    protected UserRepository $userRepository;

    public function __construct(UserRepository $userRepository)
    {
        $this->userRepository = $userRepository;
    }

    /**
     * Get all users.
     *
     * @return Collection
     */
    public function getAllUsers(): Collection
    {
        return $this->userRepository->getAll();
    }

    /**
     * Get a user by ID.
     *
     * @param int $id
     * @return User|null
     * @throws \Exception If user not found (optional, depending on desired error handling)
     */
    public function getUserById(int $id): ?User
    {
        $user = $this->userRepository->findById($id);
        if (!$user) {
            // Option 1: Return null and let controller handle it
            return null;
            // Option 2: Throw an exception for centralized error handling
            // throw new \Exception('User not found.');
        }
        return $user;
    }

    /**
     * Create a new user.
     *
     * @param array $data
     * @return User
     * @throws ValidationException
     */
    public function createUser(array $data): User
    {

        if (isset($data['password'])) {
            $data['password'] = Hash::make($data['password']);
        }

        unset($data['password_confirmation']);

        return $this->userRepository->create($data);

    }

    /**
     * Update an existing user.
     *
     * @param int $id
     * @param array $data
     * @return User|null
     * @throws ValidationException|\Exception
     */
    public function updateUser(int $id, array $data): ?User
    {
        $user = $this->userRepository->findById($id);
        if (!$user) {
            return null;
        }

        // Hash password if provided
        if (isset($data['password']) && !empty($data['password'])) {
            $data['password'] = Hash::make($data['password']);
        } else {
            // Remove password from update data if empty
            unset($data['password']);
        }

        // Remove password_confirmation
        unset($data['password_confirmation']);

        return $this->userRepository->update($id, $data);

//        $user = $this->userRepository->findById($id);
//        if (!$user) {
//            return null; // User not found
//        }
//
//        // Example of business logic: check for unique email if it's changing
//        if (isset($data['email']) && $data['email'] !== $user->email) {
//            if ($this->userRepository->model->where('email', $data['email'])->where('id', '!=', $id)->exists()) {
//                throw ValidationException::withMessages(['email' => 'The email has already been taken.']);
//            }
//        }
//
//        // DB::beginTransaction();
//        try {
//            $updatedUser = $this->userRepository->update($id, $data);
//            // Other update logic, e.g., update related profiles (Lawyer/Client)
//            // DB::commit();
//            return $updatedUser;
//        } catch (\Exception $e) {
//            // DB::rollBack();
//            throw $e;
//        }
    }

    /**
     * Delete a user (soft delete).
     *
     * @param int $id
     * @return bool
     */
    public function deleteUser(int $id): bool
    {
        // You might add checks here, e.g., prevent deleting admin users
        return $this->userRepository->delete($id);
    }

    /**
     * Restore a soft-deleted user.
     *
     * @param int $id
     * @return bool
     */
    public function restoreUser(int $id): bool
    {
        return $this->userRepository->restore($id);
    }
}
