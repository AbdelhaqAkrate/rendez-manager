<?php

namespace App\Services\Auth\API;

use Carbon\Carbon;
use App\Models\User\User;
use App\Services\Service;
use Illuminate\Auth\AuthManager;
use PHPOpenSourceSaver\JWTAuth\Token;
use Illuminate\Foundation\Application;
use PHPOpenSourceSaver\JWTAuth\JWTGuard;
use App\Services\Auth\API\EloquentUserProvider;

class AuthService extends Service
{
    public const GUARD_NAME = 'user-api';
    public const AUTH_MIDDLEWARE_NAME = 'auth:user-api';
    public const TOKEN_TTL = 60000;

    public function __construct(
        private AuthManager $authManager,
        private Application $app
    ) {
        parent::__construct();
    }

    public function user(): ?User
    {
        return $this->guard()->user();
    }

    public function guard(): JWTGuard
    {
        /** @var JWTGuard $jwtGuard */
        $jwtGuard = $this->authManager->guard(self::GUARD_NAME);
        $jwtGuard->setProvider($this->createUserProvider());

        return $jwtGuard;
    }

    public function check(): bool
    {
        return $this->guard()->check();
    }

    public function login(User $user): string
    {
        return $this->guard()->login($user);
    }

    public function logout(): void
    {
        $this->guard()->logout();
    }

    private function createUserProvider(): EloquentUserProvider
    {
        return $this->app->make(EloquentUserProvider::class);
    }

    public function getRefreshTokenTTL(): int
    {
        return $this->authManager->factory()->getTTL() * 60000;
    }

    public function refreshToken(string $token): string
    {
        return $this->guard()->manager()->refresh(new Token($token));
    }

    public function getTokenExpirationDate(): Carbon
    {
        return Carbon::now()->addMinutes(self::TOKEN_TTL);
    }
}
