<?php

namespace App\Services\Auth;

use Illuminate\Support\Str;
use App\Models\User\User;
use App\Services\Service;
use App\Log\LogParametersList;
use Illuminate\Hashing\HashManager;
use App\Services\User\UserService;
use App\Services\Feature\FeatureList;
use App\Managers\Auth\UserAuthManager;
use Illuminate\Validation\ValidationException;

class AuthService extends Service
{
    public function __construct(
        private UserService $userService,
        private HashManager $passwordHashManager,
        private UserAuthManager $userAuthManager,
    ) {
        parent::__construct();
    }

    public function findByCredentials(string $identifier, string $password): ?User
    {
        $user = $this->userService->findByEmail($identifier);
        if (!$user instanceof User) {
            return null;
        }

        if (!$this->passwordHashManager->check($password, $user->getPassword())) {
            return null;
        }

        return $user;
    }

    /**
     * @param string $email
     * @param string $password
     *
     * @return void
     * @throws ValidationException
     */
    public function authenticate(string $email, string $password, bool $rememberMe = false): void
    {
        $user = $this->findByCredentials($email, $password);

        if (!$user instanceof User) {
            throw ValidationException::withMessages(
                [
                    'email' => 'web.auth.login.alerts.failed',
                ]
            );
        }

        $this->appLogger->info('seller has been successfully logged in', [
            LogParametersList::FEATURE => FeatureList::LOGIN_USERS,
            LogParametersList::USER_ID => $user->getId(),
        ]);

        $this->userAuthManager->login($user, $rememberMe);
    }

    public function updateRememberToken(User $user, string $token): bool
    {
        return $this->userService->update(
            $user,
            [
                User::REMEMBER_TOKEN_COLUMN => $token,
            ]
        );
    }
}
