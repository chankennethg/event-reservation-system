<?php

namespace App\Exceptions;

use Illuminate\Auth\AuthenticationException;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use Illuminate\Http\JsonResponse;
use Illuminate\Validation\ValidationException;
use Symfony\Component\HttpKernel\Exception\HttpException;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Throwable;

class Handler extends ExceptionHandler
{
    /**
     * The list of the inputs that are never flashed to the session on validation exceptions.
     *
     * @var array<int, string>
     */
    protected $dontFlash = [
        'current_password',
        'password',
        'password_confirmation',
    ];

    /**
     * Register the exception handling callbacks for the application.
     */
    public function register(): void
    {
        $this->renderable(function (Throwable $exception, $request) {
            if ($request->expectsJson()) {
                if ($exception instanceof ModelNotFoundException) {
                    return response()->json([
                        'message' => 'The requested resource was not found.',
                    ], JsonResponse::HTTP_NOT_FOUND);
                }

                if ($exception instanceof AuthenticationException) {
                    return response()->json([
                        'message' => 'Unauthenticated.',
                    ], JsonResponse::HTTP_UNAUTHORIZED);
                }

                if ($exception instanceof ValidationException) {
                    return response()->json([
                        'message' => 'The given data was invalid.',
                        'errors' => $exception->errors(),
                    ], JsonResponse::HTTP_UNPROCESSABLE_ENTITY);
                }

                if ($exception instanceof NotFoundHttpException) {
                    return response()->json([
                        'message' => 'The requested resource was not found.',
                    ], JsonResponse::HTTP_NOT_FOUND);
                }

                if ($exception instanceof HttpException) {
                    return response()->json([
                        'message' => $exception->getMessage(),
                    ], $exception->getStatusCode());
                }

                return response()->json([
                    'message' => $exception->getMessage() ?: 'Oops, something went wrong.',
                ], JsonResponse::HTTP_INTERNAL_SERVER_ERROR);
            }
        });
    }
}
