<?php

namespace App\Services\User;

use App\Managers\User\PasswordResetManager;
use App\Services\Service;
use App\Models\User\PasswordResetToken;

class PasswordResetService extends Service
{
    public function __construct(
        private PasswordResetManager $passwordResetManager,
    ) {
        parent::__construct();
    }

    public function findByToken(string $token): ?PasswordResetToken
    {
        $passwordReset = $this->passwordResetManager->findByToken($token);
        if (!$passwordReset instanceof PasswordResetToken) {
            return null;
        }

        return $passwordReset;
    }
}
