<?php

namespace App\Http\Controllers\API;

use App\Models\Model;
use App\Log\AppLogger;
use Illuminate\Support\Arr;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use App\Models\User\User;
use Illuminate\Cache\RateLimiter;
use Illuminate\Http\JsonResponse;
use League\Fractal\Resource\Item;
use League\Fractal\TransformerAbstract;
use League\Fractal\Manager as FractalManager;
use Illuminate\Pagination\LengthAwarePaginator;
use App\Exceptions\Auth\UserNotLoggedInException;
use League\Fractal\Pagination\IlluminatePaginatorAdapter;
use App\Services\Auth\AuthService as UserAuthService;
use League\Fractal\Resource\Collection as FractalCollection;
use App\Services\Auth\API\AuthService as UserAPIAuthService;

class Controller
{
    protected AppLogger $logger;
    protected FractalManager $fractalManager;
    protected UserAPIAuthService $userAPIAuthService;
    protected UserAuthService $userAuthService;
    protected RateLimiter $rateLimiter;

    public function __construct()
    {
        $this->logger = app(AppLogger::class);
        $this->fractalManager = app(FractalManager::class);
        $this->userAPIAuthService = app(UserAPIAuthService::class);
        $this->userAuthService = app(UserAuthService::class);
        $this->rateLimiter = app(RateLimiter::class);
    }

    /**
     * @return User
     * @throws UserNotLoggedInException
     */
    public function getCurrentUser(): User
    {
        $user = $this->userAPIAuthService->user();

        if (!$user instanceof User) {
            throw new UserNotLoggedInException();
        }

        return $user;
    }

    protected function successResponse(string $message, int $statusCode = Response::HTTP_OK): JsonResponse
    {
        return response()
            ->json(['message' => $message], $statusCode);
    }

    protected function successResponseWithItem(
        string $message,
        ?array $payload = [],
        int $statusCode = Response::HTTP_OK
    ): JsonResponse {
        return response()
            ->json(['message' => $message, 'payload' => $payload], $statusCode);
    }

    protected function errorResponse(
        string $message,
        int $statusCode = Response::HTTP_INTERNAL_SERVER_ERROR
    ): JsonResponse {
        return response()
            ->json(
                [
                    'message' => $message,
                ],
                $statusCode
            );
    }

    protected function itemResponse(Model $item, TransformerAbstract $transformer): JsonResponse
    {
        $this->parseGetIncludes();

        return response()
            ->json($this->transformItem($item, $transformer), Response::HTTP_OK);
    }

    protected function arrayResponse(array $data, int $statusCode = Response::HTTP_OK): JsonResponse
    {
        return response()
            ->json($data, $statusCode);
    }

    private function parseGetIncludes(): void
    {
        $requestedIncludes = $this->fractalManager->getRequestedIncludes();
        $this->fractalManager->parseIncludes((string)Arr::get($_GET, 'include', ''));

        $this->fractalManager->parseIncludes(
            array_merge($requestedIncludes, $this->fractalManager->getRequestedIncludes())
        );
    }

    protected function transformItem(Model $item, TransformerAbstract $transformer): array
    {
        $this->parseGetIncludes();

        $fractalResource = new Item($item, $transformer);
        $scope = $this->fractalManager->createData($fractalResource);

        return $scope->toArray();
    }

    public function withPaginator(
        LengthAwarePaginator $paginator,
        callable|TransformerAbstract $transformer,
    ): JsonResponse {
        $data = $paginator->getCollection();

        $resource = new FractalCollection($data, $transformer);
        $resource->setPaginator(new IlluminatePaginatorAdapter($paginator));

        $rootScope = $this->fractalManager->createData($resource);

        return new JsonResponse($rootScope->toArray(), Response::HTTP_OK);
    }

    public function throttleKey(Request $request, string $keyInfo): string
    {
        return Str::lower($request->input('email')) . '|' . $request->ip() . '|' . $keyInfo;
    }

    public function ensureIsNotRateLimited(Request $request, string $keyInfo, int $maxAttempts)
    {
        $key = $this->throttleKey($request, $keyInfo);

        if (!$this->rateLimiter->tooManyAttempts($key, $maxAttempts)) {
            return;
        }

        $response = [
            'message' => 'Too many failed login attempts. Your IP is restricted for 10 minutes.',
        ];

        return $this->arrayResponse($response, Response::HTTP_TOO_MANY_REQUESTS);
    }
}
