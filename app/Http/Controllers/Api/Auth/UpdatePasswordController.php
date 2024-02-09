<?php

namespace App\Http\Controllers\API\Auth;

use Throwable;
use Carbon\Carbon;
use App\Models\User\User;
use App\Log\LogParametersList;
use Illuminate\Support\Facades\Hash;
use App\Services\User\UserService;
use App\Http\Controllers\API\Controller;
use App\Services\Feature\FeatureList;
use App\Services\User\PasswordResetService;
use App\Http\Requests\Auth\UpdatePasswordRequest;

class UpdatePasswordController extends Controller
{
    public function __construct(
        private UserService $userService,
        private PasswordResetService $passwordResetService,
    ) {
        parent::__construct();
    }

    public function __invoke(UpdatePasswordRequest $request, string $token)
    {
        try {
            $passwordReset = $this->passwordResetService->findByToken($token);
            if (!$passwordReset) {
                return $this->errorResponse('Token not found.', 404);
            }

            $createdAt = Carbon::parse($passwordReset->created_at);
            $expiresInMinutes = config('auth.passwords.users.expire');

            if (Carbon::now()->diffInMinutes($createdAt) > $expiresInMinutes) {
                return $this->errorResponse('Token has expired.', 400);
            }

            $user = $this->userService->findByEmail($passwordReset->getEmail());
            if (!$user instanceof User) {
                return $this->errorResponse('User not found.', 400);
            }

            $this->userService->update($user, [
                User::PASSWORD_COLUMN => Hash::make($request->input('password')),
            ]);

            return $this->successResponse('Password updated successfully.');
        } catch (Throwable $e) {
            $this->logger->error(
                'error while trying to updating the password',
                [
                    LogParametersList::FEATURE       => FeatureList::API_AUTH,
                    LogParametersList::SUBFEATURE    => FeatureList::API_UPDATE_PASSWORD,
                    LogParametersList::ERROR_MESSAGE => $e->getMessage(),
                    LogParametersList::ERROR_TRACE   => getExceptionTraceAsString($e),
                ]
            );

            return $this->errorResponse('An error occurred while processing your request. Please try again later.');
        }
    }
}
