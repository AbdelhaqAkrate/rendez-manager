<?php

namespace App\Http\Controllers\API\Auth;

use Throwable;
use Illuminate\Http\Request;
use App\Core\Log\LogParametersList;
use App\Http\Controllers\API\Controller;
use App\Core\Services\Feature\FeatureList;

class RefreshTokenController extends Controller
{
    public function __invoke(Request $request)
    {
        try {
            $token = $this->userAPIAuthService->refreshToken($request->bearerToken());
            $expiresIn = $this->userAPIAuthService->getRefreshTokenTTL();

            $this->logger->info(
                'token has been successfully refreshed',
                [
                    LogParametersList::FEATURE    => FeatureList::API_AUTH,
                    LogParametersList::SUBFEATURE => FeatureList::API_REFRESH_TOKEN,
                    LogParametersList::USER_ID  => $this->getCurrentUser()->getId(),
                ]
            );

            return $this->arrayResponse(
                [
                    'token'      => $token,
                    'expires_in' => $expiresIn,
                ]
            );
        } catch (Throwable $e) {
            $this->logger->error(
                'error while trying to refresh token',
                [
                    LogParametersList::FEATURE       => FeatureList::API_AUTH,
                    LogParametersList::SUBFEATURE    => FeatureList::API_REFRESH_TOKEN,
                    LogParametersList::ERROR_MESSAGE => $e->getMessage(),
                    LogParametersList::ERROR_TRACE   => getExceptionTraceAsString($e),
                ]
            );

            return $this->errorResponse('An error occurred while processing your request. Please try again later.');
        }
    }
}
