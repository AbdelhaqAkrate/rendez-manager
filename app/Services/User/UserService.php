<?php

namespace App\Services\User;

use App\Models\User\User;
use App\Services\Service;
use App\Log\LogParametersList;
use App\Managers\User\UserManager;
use App\Services\Feature\FeatureList;

class UserService extends Service
{
    public function __construct(
        private UserManager $userManager,
    ) {
        parent::__construct();
    }

    public function findByEmail(string $email): ?User
    {
        $user = $this->userManager->findByEmail($email);
        
        $this->appLogger->info('finding user by email', [
            LogParametersList::FEATURE   => FeatureList::ACCOUNT_USERS,
            LogParametersList::USER_ID => $user?->getId(),
            LogParametersList::EXTRA     => json_encode(
                [
                    'user.email'      => $user?->getEmail(),
                    'user.phone'      => $user?->getPhone(),
                    'user.last_name' => $user?->getName(),
                    'user.first_name'  => $user?->getPrenom(),
                ]
            ),
        ]);

        return $user;
    }

    public function findById(string $id): ?User
    {
        return $this->userManager->findById($id);
    }

    public function update(User $user, array $attributes): bool
    {
        return $this->userManager->update($user, $attributes);
    }
}
