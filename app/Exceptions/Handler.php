<?php

namespace App\Exceptions;

use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use Throwable;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class Handler extends ExceptionHandler
{
    /**
     * A list of exception types with their corresponding custom log levels.
     *
     * @var array<class-string<\Throwable>, \Psr\Log\LogLevel::*>
     */
    protected $levels = [
        //
    ];

    /**
     * A list of the exception types that are not reported.
     *
     * @var array<int, class-string<\Throwable>>
     */
    protected $dontReport = [
        //
    ];

    /**
     * A list of the inputs that are never flashed to the session on validation exceptions.
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
        $this->reportable(function (Throwable $e) {
            //
        });
    }

    public function render($request, Throwable $exception)
    {
        // Return 404 if a NotFoundHttpException or any exception occurs
        if ($exception instanceof NotFoundHttpException) {
            //return response()->view('errors.404', [], 404);  // Load the 404 view
        }

        // Optionally, catch other exceptions and convert them into 404 errors
        if ($exception instanceof \Exception || $exception instanceof \Throwable) {
            // You can either throw a NotFoundHttpException or directly return the response
            //return response()->view('errors.404', [], 404); // Custom 404 response
        }

        // Default behavior for other exceptions
        return parent::render($request, $exception);
    }
}
