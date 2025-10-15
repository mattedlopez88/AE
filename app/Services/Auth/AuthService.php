<?php

namespace App\Services\Auth;

use App\Repositories\UserRepositoryInterface;
use App\Models\User;
use Illuminate\Validation\ValidationException;

class AuthService
{
    public function __construct(
        private UserRepositoryInterface $userRepository
    ) {}

    public function register(array $userData): array
    {
        $user = $this->userRepository->create($userData);

        return [
            'user' => $user,
            'token' => $user->createToken('auth-token')->plainTextToken
        ];
    }

    public function createTokenForUser(User $user): string
    {
        return $user->createToken('auth-token')->plainTextToken;
    }

    public function revokeUserTokens(User $user): void
    {
        $user->currentAccessToken()->delete();
    }
}
