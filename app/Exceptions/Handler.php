<?php

namespace App\Exceptions;

use App\Support\Exceptions\CustomModelNotFoundException;
use App\Support\Exceptions\CustomNotFoundHttpException;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Database\QueryException;
use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use Log;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Throwable;

class Handler extends ExceptionHandler
{
    /**
     * A list of the exception types that are not reported.
     *
     * @var array<int, class-string<Throwable>>
     */
    protected $dontReport = [
        //
    ];

    /**
     * A list of the inputs that are never flashed for validation exceptions.
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
     *
     * @return void
     */
    public function register()
    {
        $this->reportable(function (Throwable $e) {
            //

        });

        $this->renderable(function (NotFoundHttpException $exception) {
            //
            throw new CustomNotFoundHttpException();
        });


        $this->renderable(function (QueryException $exception) {
            //

            throw new CustomModelNotFoundException($exception);
        });
    }

    public function render($request, Throwable $exception)
    {
        if ($exception instanceof ModelNotFoundException) {

            throw new CustomModelNotFoundException($exception);
        }

        return parent::render($request, $exception);
    }
}
