<?php

namespace App\Http\Controllers\API\Auth;

use Throwable;
use App\Log\LogParametersList;
use App\Http\Controllers\API\Controller;
use App\Services\Feature\FeatureList;

class LogoutController extends Controller
{
    public function __invoke()
    {
        try {
            
            $this->logger->info(
                'user has been successfully logged out',
                [
                    LogParametersList::FEATURE    => FeatureList::API_AUTH,
                    LogParametersList::SUBFEATURE => FeatureList::API_LOGOUT_USERS,
                    LogParametersList::USER_ID  => $this->getCurrentUser()->getId(),
                    ]
                );        
             $this->userAPIAuthService->logout();

            return $this->successResponse('You have been successfully logged out.');
        } catch (Throwable $e) {
            $this->logger->error(
                'error while trying to logout',
                [
                    LogParametersList::FEATURE       => FeatureList::API_AUTH,
                    LogParametersList::SUBFEATURE    => FeatureList::API_LOGOUT_USERS,
                    LogParametersList::ERROR_MESSAGE => $e->getMessage(),
                    LogParametersList::ERROR_TRACE   => getExceptionTraceAsString($e),
                ]
            );

            return $this->errorResponse('An error occurred while processing your request. Please try again later.');
        }
    }
}
