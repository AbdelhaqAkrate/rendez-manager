<?php

namespace App\Repositories\User;

use Illuminate\Support\Arr;
use App\Models\User\User;
use App\Repositories\Repository;

class UserRepository extends Repository
{
    public function findById(string $id): ?User
    {
        return User::query()
            ->where(User::ID_COLUMN, $id)
            ->first();
    }

    public function findByEmail(string $email): ?User
    {
        return User::query()
            ->where(User::EMAIL_COLUMN, $email)
            ->first();
    }

    public function update(string $id, array $attributes): bool
    {
        $attributes = Arr::only(
            $attributes,
            [
                User::PHONE_COLUMN,
                User::EMAIL_COLUMN,
                User::EMAIL_VERIFIED_AT_COLUMN,
                User::REMEMBER_TOKEN_COLUMN,
                User::PASSWORD_COLUMN,
                User::STATUS_COLUMN,
            ]
        );

        return User::query()
                ->where(User::ID_COLUMN, $id)
                ->update($attributes) > 0;
    }
}
