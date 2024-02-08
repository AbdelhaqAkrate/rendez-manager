<?php

namespace App\Http\Controllers\Api\Auth;

use Throwable;
use Illuminate\Support\Arr;
use App\Models\User\User;
use Illuminate\Support\Facades\RateLimiter;
use App\Log\LogParametersList;
use App\Http\Controllers\API\Controller;
use App\Http\Requests\Auth\LoginRequest;
use App\Services\Feature\FeatureList;
use Symfony\Component\HttpFoundation\Response;

class LoginController extends Controller
{
    public function __invoke(LoginRequest $request)
    {
        $rateLimitResponse = $this->ensureIsNotRateLimited($request, 'login', 5);
        if ($rateLimitResponse) {
            return $rateLimitResponse;
        }

        try {
            $validatedAttributes = $request->validated();
            $password = Arr::get($validatedAttributes, 'password');
            $email = Arr::get($validatedAttributes, 'email');
            $user = $this->userAuthService->findByCredentials($email, $password);
            if (!$user instanceof User) {
                RateLimiter::hit($this->throttleKey($request, 'login'), 600);

                return $this->errorResponse(
                    'Invalid credentials. Please check your email and password.',
                    Response::HTTP_UNPROCESSABLE_ENTITY
                );
            }

            $this->logger->info('user has been logged in via api', [
                LogParametersList::FEATURE    => FeatureList::API_AUTH,
                LogParametersList::SUBFEATURE => FeatureList::API_LOGIN_USERS,
                LogParametersList::USER_ID    => $user->getId(),
            ]);

            RateLimiter::clear($this->throttleKey($request, 'login'));

            return $this->arrayResponse(
                [
                    'token' => $this->userAPIAuthService->login($user),
                    'as'    => 'user',
                ]
            );
        } catch (Throwable $e) {
            $this->logger->error(
                'error while trying to login user',
                [
                    LogParametersList::FEATURE       => FeatureList::API_AUTH,
                    LogParametersList::SUBFEATURE    => FeatureList::API_LOGIN_USERS,
                    LogParametersList::ERROR_MESSAGE => $e->getMessage(),
                    LogParametersList::ERROR_TRACE   => getExceptionTraceAsString($e),
                ]
            );

            return $this->errorResponse('An error occurred while processing your request. Please try again later.');
        }
    }
}
