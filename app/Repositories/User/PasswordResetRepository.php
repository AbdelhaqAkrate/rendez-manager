<?php

namespace App\Repositories\User;

use App\Repositories\Repository;
use App\Models\User\PasswordResetToken;

class PasswordResetRepository extends Repository
{
    public function findByToken(string $token): ?PasswordResetToken
    {
        return PasswordResetToken::query()
            ->where(PasswordResetToken::TOKEN_COLUMN, $token)
            ->first();
    }
}
