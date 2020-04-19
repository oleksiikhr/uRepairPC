<?php declare(strict_types=1);

namespace App\Exceptions;

use Throwable;
use Illuminate\Validation\ValidationException;
use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use Symfony\Component\HttpKernel\Exception\UnauthorizedHttpException;

class Handler extends ExceptionHandler
{
    /**
     * @inheritDoc
     */
    protected $dontReport = [
        //
    ];

    /**
     * @inheritDoc
     */
    protected $dontFlash = [
        'password',
        'password_confirmation',
    ];

    /**
     * @inheritDoc
     */
    public function report(Throwable $exception): void
    {
        parent::report($exception);
    }

    /**
     * @inheritDoc
     */
    public function render($request, Throwable $exception)
    {
        // Catch validation exception
        if ($exception instanceof ValidationException) {
            return response()->json([
                'message' => __('exception.validation'),
                'errors' => $exception->validator->getMessageBag(),
            ], 422);
        }

        // Catch unauthorized exception (no token, etc)
        if ($exception instanceof UnauthorizedHttpException) {
            return response()->json([
                'message' => __('exception.unauthorized_http'),
            ], 401);
        }

        return parent::render($request, $exception);
    }
}
