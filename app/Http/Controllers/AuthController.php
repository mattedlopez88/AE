<?php

namespace App\Http\Controllers;

use App\Http\Requests\Auth\LoginRequest;
use App\Http\Requests\Auth\RegisterRequest;
use App\Services\Auth\AuthService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class AuthController extends Controller
{
    public function __construct(
        private AuthService $authService
    ){}

    public function register(RegisterRequest $request): JsonResponse
    {
        $result  = $this->authService->register($request);

        return response()->json([
            'message' => 'Registration successful',
            'data' => [
                'token' => $result['token'],
                'user' => $result['user']
            ]
        ], 201);
    }

    public function login(LoginRequest $request): JsonResponse
    {
        $user = $request->validateCredentials();
        $token = $this->authService->createTokenForUser($user);

        return response()->json([
            'message' => 'Login successful',
            'data' => [
                'token' => $token,
                'user' => $user
            ]
        ]);
    }

    public function logout(Request $request): JsonResponse
    {
        $this->authService->revokeUserTokens($request->user());

        return response()->json([
            'message' => 'Logged out successfully'
        ]);
    }
}
