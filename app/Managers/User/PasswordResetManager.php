<?php

namespace App\Managers\User;

use App\Models\User\PasswordResetToken;
use App\Repositories\User\PasswordResetRepository;

class PasswordResetManager
{
    public function __construct(
        private PasswordResetRepository $passwordResetRepository
    ) {
    }

    public function findByToken(string $token): ?PasswordResetToken
    {
        return $this->passwordResetRepository->findByToken($token);
    }
}
