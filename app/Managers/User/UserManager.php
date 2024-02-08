<?php

namespace App\Managers\User;

use App\Managers\Manager;
use App\Models\User\User;
use App\Repositories\User\UserRepository;

class UserManager extends Manager
{
    public function __construct(
        private UserRepository $userRepository
    ) {
    }

    public function findById(string $id): ?User
    {
        return $this->userRepository->findById($id);
    }

    public function findByEmail(string $email): ?User
    {
        return $this->userRepository->findByEmail($email);
    }

    public function update(User $user, array $attributes): bool
    {
        return $this->userRepository->update($user->getId(), $attributes);
    }
}
