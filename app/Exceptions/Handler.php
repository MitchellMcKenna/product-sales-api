<?php

namespace App\Exceptions;

use Exception;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use Illuminate\Http\Response;
use Symfony\Component\HttpKernel\Exception\MethodNotAllowedHttpException;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class Handler extends ExceptionHandler
{
    /**
     * A list of the exception types that are not reported.
     *
     * @var array
     */
    protected $dontReport = [
        InputValidationException::class
    ];

    /**
     * Report or log an exception.
     *
     * This is a great spot to send exceptions to Sentry, Bugsnag, etc.
     *
     * @param  \Exception  $exception
     * @return void
     */
    public function report(Exception $exception)
    {
        parent::report($exception);
    }

    /**
     * Render an exception into an HTTP response.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Exception  $e
     * @return \Illuminate\Http\Response
     */
    public function render($request, Exception $e)
    {
        if ($e instanceof MethodNotAllowedHttpException) {
            return new Response(
                ['errors' => [['code' => 4, 'title' => 'Method not allowed.']]],
                Response::HTTP_METHOD_NOT_ALLOWED
            );
        }

        if ($e instanceof NotFoundHttpException || $e instanceof ModelNotFoundException) {
            return new Response(
                ['errors' => [['code' => 1, 'title' => 'Not found.']]],
                Response::HTTP_NOT_FOUND
            );
        }

        // Render errors when debug is on, else catch them and report 404 or fallback to 500 error response.
        if (env('APP_DEBUG')) {
            return parent::render($request, $e);
        }

        return new Response(
            ['errors' => [['code' => 3, 'title' => 'Undefined server error.']]],
            Response::HTTP_INTERNAL_SERVER_ERROR
        );
    }
}
