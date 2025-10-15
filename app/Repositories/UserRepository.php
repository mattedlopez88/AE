<?php

namespace App\Repositories;

use App\Models\User;
use Illuminate\Database\Eloquent\Collection;

class UserRepository implements UserRepositoryInterface
{
    protected User $model;

    public function __construct(User $user)
    {
        $this->model = $user;
    }

    /**
     * Get all users.
     *
     * @return Collection
     */
    public function getAll(): Collection
    {
        return $this->model->all();
    }

    /**
     * Get a user by ID.
     *
     * @param int $id
     * @return User|null
     */
    public function findById(int $id): ?User
    {
        return $this->model->find($id);
    }

    /**
     * Create a new user.
     *
     * @param array $data
     * @return User
     */
    public function create(array $data): User
    {
        // Hash password before saving
        $data['password'] = bcrypt($data['password']);
        return $this->model->create($data);
    }

    /**
     * Update an existing user.
     *
     * @param int $id
     * @param array $data
     * @return User|null
     */
    public function update(int $id, array $data): ?User
    {
        $user = $this->findById($id);
        if ($user) {
            // Only hash password if it's provided and not empty
            if (isset($data['password']) && !empty($data['password'])) {
                $data['password'] = bcrypt($data['password']);
            } else {
                // Remove password from data if not provided to avoid overwriting with empty
                unset($data['password']);
            }
            $user->update($data);
        }
        return $user;
    }

    /**
     * Delete a user by ID (soft delete).
     *
     * @param int $id
     * @return bool
     */
    public function delete(int $id): bool
    {
        $user = $this->findById($id);
        if ($user) {
            return $user->delete(); // Soft deletes if SoftDeletes trait is used
        }
        return false;
    }

    /**
     * Restore a soft-deleted user by ID.
     *
     * @param int $id
     * @return bool
     */
    public function restore(int $id): bool
    {
        $user = $this->model->onlyTrashed()->find($id); // Find in trash
        if ($user) {
            return $user->restore();
        }
        return false;
    }

    public function findByEmail(string $email): ?User
    {
        return $this->model->find()->where('email', $email)->first();
    }
}
