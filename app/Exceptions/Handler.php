<?php declare(strict_types=1);

namespace App\Exceptions;

use Throwable;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
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
     * A list of the inputs that are never flashed for validation exceptions.
     *
     * @var array
     */
    protected $dontFlash = [
        'password',
        'password_confirmation',
    ];

    /**
     * Report or log an exception.
     *
     * @param  \Throwable  $exception
     * @return void
     *
     * @throws \Exception
     */
    public function report(Throwable $exception)
    {
        parent::report($exception);
    }

    /**
     * Render an exception into an HTTP response.
     *
     * @param  Request  $request
     * @param  Throwable  $exception
     * @return Response
     * @throws Throwable
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
