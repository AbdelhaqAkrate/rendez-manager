<?php

namespace App\Http\Controllers\API\Auth;

use Throwable;
use Illuminate\Http\Response;
use App\Models\User\User;
use App\Log\LogParametersList;
use App\Services\User\UserService;
use App\Http\Controllers\API\Controller;
use Illuminate\Support\Facades\Password;
use App\Services\Feature\FeatureList;
use Illuminate\Support\Facades\RateLimiter;
use App\Http\Requests\Auth\ResetPasswordRequest;

class PasswordResetController extends Controller
{
    public function __construct(
        private UserService $userService,
    ) {
        parent::__construct();
    }

    public function __invoke(ResetPasswordRequest $request)
    {
        $rateLimitResponse = $this->ensureIsNotRateLimited($request, 'resetPassword', 3);
        if ($rateLimitResponse) {
            return $rateLimitResponse;
        }

        try {
            $email = $request->input('email');
            $user = $this->userService->findByEmail($email);
            if (!$user instanceof User) {
                return $this->errorResponse('Email address not found in our records.', Response::HTTP_BAD_REQUEST);
            }

            $this->broker()->sendResetLink(['email' => $email]);
            RateLimiter::hit($this->throttleKey($request, 'resetPassword'), 60);

            return $this->successResponse('Reset password link sent. Please check your email.');
        } catch (Throwable $e) {
            $this->logger->error(
                'error while trying to send reset password link',
                [
                    LogParametersList::FEATURE       => FeatureList::API_AUTH,
                    LogParametersList::SUBFEATURE    => FeatureList::API_RESET_PASSWORD_LINK,
                    LogParametersList::ERROR_MESSAGE => $e->getMessage(),
                    LogParametersList::ERROR_TRACE   => getExceptionTraceAsString($e),
                ]
            );

            return $this->errorResponse('An error occurred while processing your request. Please try again later.');
        }
    }

    private function broker()
    {
        return Password::broker();
    }
}
