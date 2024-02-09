<?php

namespace App\Managers\Auth;

use App\Models\User\User;
use Illuminate\Auth\AuthManager;

class UserAuthManager
{
    public const GUARD_NAME = 'web';

    public function __construct(private AuthManager $authManager)
    {
    }

    public function user(): ?User
    {
        return $this->guard()->user();
    }

    public function guard()
    {
        return $this->authManager->guard(self::GUARD_NAME);
    }

    public function check(): bool
    {
        return $this->guard()->check();
    }

    public function login(User $seller, bool $rememberMe = false): void
    {
        $this->guard()->login($seller, $rememberMe);
    }

    public function logout(): void
    {
        $this->guard()->logout();
    }
}
