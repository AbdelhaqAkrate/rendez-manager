<?php

namespace App\Services\Auth\API;

use Illuminate\Support\Arr;
use App\Models\User\User;
use App\Services\User\UserService;
use Illuminate\Contracts\Auth\UserProvider;
use Illuminate\Contracts\Auth\Authenticatable;
use App\Services\Auth\AuthService As UserAuthService;

class EloquentUserProvider implements UserProvider
{
    public function __construct(
        private UserService $userService,
        private UserAuthService $userAuthService
    ) {
    }

    public function retrieveById($identifier): ?User
    {
        return $this->userService->findById($identifier);
    }

    public function retrieveByToken($identifier, $token): User|null
    {
        $user = $this->userService->findById($identifier);
        if (!$user instanceof User) {
            return null;
        }

        return $user->getRememberToken() && hash_equals($user->getRememberToken(), $token)
            ? $user : null;
    }

    public function updateRememberToken(Authenticatable $user, $token): bool
    {
        return $this->userAuthService->updateRememberToken($user, $token);
    }

    public function retrieveByCredentials(array $credentials): ?User
    {
        if (!Arr::has($credentials, ['email', 'password'])) {
            return null;
        }

        return $this->userAuthService->findByCredentials($credentials['email'], $credentials['password']);
    }

    public function validateCredentials(Authenticatable $user, array $credentials): bool
    {
        return $this->retrieveByCredentials($credentials) instanceof User;
    }
}
