<?php

namespace App\Exceptions;

use Exception;
use Illuminate\Auth\AuthenticationException;
use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;

class Handler extends ExceptionHandler
{
    /**
     * A list of the exception types that should not be reported.
     *
     * @var array
     */
    protected $dontReport = [
        \Illuminate\Auth\AuthenticationException::class,
        \Illuminate\Auth\Access\AuthorizationException::class,
        \Symfony\Component\HttpKernel\Exception\HttpException::class,
        \Illuminate\Database\Eloquent\ModelNotFoundException::class,
        \Illuminate\Session\TokenMismatchException::class,
        \Illuminate\Validation\ValidationException::class,
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
     * @param  \Exception  $exception
     * @return \Illuminate\Http\Response
     */
    public function render($request, Exception $exception)
    {
        $error = $this->convertExceptionToResponse($exception);
        $response = [];
        if($error->getStatusCode() == 500) {
            $response['status'] = false;
            $response['error'] = $exception->getMessage();
            $response['trace'] = $exception->getTrace()[0];
            $response['code'] = $exception->getCode();
        }
        elseif($error->getStatusCode() == 404) {
            $response['status'] = false;
            $response['error'] = 'Page Not Found';
        }
        elseif($error->getStatusCode() == 403) {
            $response['status'] = false;
            $response['error'] = 'Forbidden to Access here. Bye!';
        }
        else{
            $response['status'] = false;
            $response['error'] = 'I\'m as shocked as you are. What Happened? You might be running the wrong method. Post or Get?.';

        }
        return response()->json($response, $error->getStatusCode());
        //return parent::render($request, $exception);
    }

    /**
     * Convert an authentication exception into an unauthenticated response.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Illuminate\Auth\AuthenticationException  $exception
     * @return \Illuminate\Http\Response
     */
    protected function unauthenticated($request, AuthenticationException $exception)
    {
        if ($request->expectsJson()) {
            return response()->json(['error' => 'Unauthenticated.'], 401);
        }

        return redirect()->guest(route('login'));
    }
}
