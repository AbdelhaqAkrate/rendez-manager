<?php

namespace App\Http\Controllers\API\Auth;

use Throwable;
use Carbon\Carbon;
use App\Log\LogParametersList;
use App\Http\Controllers\API\Controller;
use App\Services\Feature\FeatureList;
use App\Services\User\PasswordResetService;
use Illuminate\Support\Facades\Hash;

class PasswordResetCheckTokenController extends Controller
{
    public function __construct(
        private PasswordResetService $passwordResetService,
    ) {
        parent::__construct();
    }

    public function __invoke(string $token)
    {
        try {
            $passwordReset = $this->passwordResetService->findByToken(Hash::make($token));
            if (!$passwordReset) {
                return $this->errorResponse('Token not found.', 404);
            }

            if ($passwordReset->isExpired()) {
                return $this->errorResponse('Token has expired.', 400);
            }

            return $this->successResponse('Token is valid.');
        } catch (Throwable $e) {
            $this->logger->error(
                'error while trying to check reset password token',
                [
                    LogParametersList::FEATURE       => FeatureList::API_AUTH,
                    LogParametersList::SUBFEATURE    => FeatureList::API_CHECK_RESET_PASSWORD_TOKEN,
                    LogParametersList::ERROR_MESSAGE => $e->getMessage(),
                    LogParametersList::ERROR_TRACE   => getExceptionTraceAsString($e),
                ]
            );

            return $this->errorResponse('An error occurred while processing your request. Please try again later.');
        }
    }
}
