<?php

namespace App\Exceptions;

use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Auth\AuthenticationException;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use Illuminate\Session\TokenMismatchException;
use Illuminate\Validation\ValidationException;
use Symfony\Component\HttpKernel\Exception\MethodNotAllowedHttpException;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\HttpKernel\Exception\UnauthorizedHttpException;
use Throwable;

class Handler extends ExceptionHandler
{
    protected $dontReport = [
        \Illuminate\Auth\AuthenticationException::class,
        \Illuminate\Auth\Access\AuthorizationException::class,
        \Symfony\Component\HttpKernel\Exception\HttpException::class,
        \Illuminate\Database\Eloquent\ModelNotFoundException::class,
        \Illuminate\Session\TokenMismatchException::class,
        \Illuminate\Validation\ValidationException::class,
    ];

    protected $dontFlash = [
        'password',
        'password_confirmation',
    ];

    public function report(Throwable $exception)
    {
        parent::report($exception);
    }

    public function render($request, Throwable $exception)
    {
//        dd($exception);
        if (strpos($request->url(), '/api/') !== false || strpos($request->url(), '/web/') !== false) {
            abort(404);
            \Log::debug('API Request Exception - ' . $request->url() . ' - ' . $exception->getMessage() . (!empty($request->all()) ? ' - ' . json_encode($request->except(['password'])) : ''));

            if ($exception instanceof AuthorizationException) {
                return $this->setStatusCode(403)->respondWithError($exception->getMessage());
            }

            if ($exception instanceof MethodNotAllowedHttpException) {
                return $this->setStatusCode(403)->respondWithError('Please check HTTP Request Method. - MethodNotAllowedHttpException');
            }

            if ($exception instanceof AuthenticationException) {
                return $this->setStatusCode(401)->respondWithError('Unauthenticated');
            }

            if ($exception instanceof NotFoundHttpException) {
                return $this->setStatusCode(403)->respondWithError('Please check your URL to make sure request is formatted properly. - NotFoundHttpException');
            }


            if ($exception instanceof GeneralException) {
                return $this->setStatusCode(403)->respondWithError($exception->getMessage());
            }

            if ($exception instanceof ModelNotFoundException) {
                return $this->setStatusCode(403)->respondWithError(api('The requested item is not available'));
            }

            if ($exception instanceof ValidationException) {
                \Log::debug('API Validation Exception - ' . json_encode($exception->validator->messages()) . (!empty($request->all()) ? ' - ' . json_encode($request->except(['password'])) : ''));
                $error = "";
                if ($exception->validator->fails()) {
                    $messages = $exception->validator->messages()->toArray();
                    foreach ($messages as $key => $message) {
                        $error = $message[0];
                        break;
                    }
                }
                return $this->setStatusCode(422)->respondWithError($error);
            }

            /*
            * Redirect if token mismatch error
            * Usually because user stayed on the same screen too long and their session expired
            */
            if ($exception instanceof UnauthorizedHttpException) {
                switch (get_class($exception->getPrevious())) {
                    case \App\Exceptions\Handler::class:
                        return $this->setStatusCode($exception->getStatusCode())->respondWithError('Token has not been provided.');
                }
            } else {
                return $this->setStatusCode(500)->respondWithError($exception->getMessage());
            }

        }

        /*
         * Redirect if token mismatch error
         * Usually because user stayed on the same screen too long and their session expired
         */
        if ($exception instanceof TokenMismatchException) {
            if (strpos($request->url(), '/api/') !== false) {
                return $this->setStatusCode(401)->respondWithError('Unauthenticated');
            }

            switch (strpos($request->url(), '/manager/')) {
                case true:
                    $login = '/manager/login';
                    break;
                default:
                    $login = '/';
                    break;
            }
            return redirect()->guest($login);
        }

        /*
         * All instances of GeneralException redirect back with a flash message to show a bootstrap alert-error
         */
        if ($exception instanceof GeneralException) {
            session()->flash('dontHide', $exception->dontHide);

            return redirect()->back()->withInput()->withFlashDanger($exception->getMessage());
        }

        if ($exception instanceof \Spatie\Permission\Exceptions\UnauthorizedException) {
            if (strpos($request->url(), '/api/') !== false) {
                return response()->json(['User have not permission for this page access.']);
            } else {
                return redirect()->route('manager.home')->with('message', t('User have not permission for this page access.'))->with('m-class', 'error');
            }

        }
        return parent::render($request, $exception);
    }


    protected function unauthenticated($request, AuthenticationException $exception)
    {
        if (strpos($request->url(), '/api/') !== false) {
            return $this->setStatusCode(401)->respondWithError('Unauthenticated');
        }

        $guard = array_get($exception->guards(), 0);
        switch ($guard) {
            case 'manager':
                $login = 'manager/login';
                break;
            default:
                $login = '/';
                break;
        }
        return redirect()->guest($login);


    }

    public function getStatusCode()
    {
        return $this->statusCode;
    }

    public function setStatusCode($statusCode)
    {
        $this->statusCode = $statusCode;

        return $this;
    }

    protected function respondWithError($message)
    {
        return $this->respond([
            'success' => false,
            'data' => null,
            'status' => $this->getStatusCode(),
            'message' => $message,
        ]);
    }

    public function respond($data, $headers = [])
    {
        return response()->json($data, $this->getStatusCode(), $headers);
    }
}
