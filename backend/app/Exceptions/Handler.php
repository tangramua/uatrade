<?php

namespace App\Exceptions;

use Doctrine\DBAL\Exception\TableNotFoundException;
use Exception;
use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use Illuminate\Validation\ValidationException;
use Modules\Core\Extensions\LocalizationExtension;
use Symfony\Component\HttpKernel\Exception\MethodNotAllowedHttpException;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\HttpKernel\Exception\UnauthorizedHttpException;
use Symfony\Component\Routing\Exception\MethodNotAllowedException;
use Tymon\JWTAuth\Exceptions\TokenExpiredException;
use Tymon\JWTAuth\Exceptions\TokenInvalidException;

class Handler extends ExceptionHandler
{
    /**
     * A list of the exception types that are not reported.
     *
     * @var array
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
     * @param Exception $exception
     * @return mixed|void
     * @throws Exception
     */
    public function report(Exception $exception)
    {
        parent::report($exception);
    }


    /**
     * Render an exception into an HTTP response.
     * @param  \Illuminate\Http\Request  $request
     * @param  \Exception  $exception
     * @return \Illuminate\Http\Response
     */
    public function render($request, Exception $exception)
    {
        if ($exception instanceof TokenExpiredException) {
            return response()->json(['message'=>'token_expired', 'status'=>'error', 'error'=>true], 401);
        } else if ($exception instanceof UnauthorizedHttpException) {
            return response()->json(['message'=>$exception->getMessage(), 'status'=>'error', 'error'=>true], 401);
        } else if ($exception instanceof TokenInvalidException || $exception instanceof PermissionException) {
            return response()->json(['message'=>$exception->getMessage(), 'status'=>'error', 'error'=>true], 403);
        }else if ($exception instanceof MethodNotAllowedException) {
            return response()->json(['message'=>$exception->getMessage().' Please use following methods: '.implode('; ',$exception->getAllowedMethods()), 'status'=>'error', 'error'=>true], 405);
        }else if ($exception instanceof MethodNotAllowedHttpException) {
            return response()->json(['message'=>$exception->getMessage()? $exception->getMessage() : ' Method Not Allowed.', 'status'=>'error', 'error'=>true], 405);
        }else if ($exception instanceof TableNotFoundException) {
            return response()->json(['message'=>'Page not found.', 'status'=>'error', 'error'=>true], 404);
        }else if (($exception instanceof NotFoundException)||($exception instanceof NotFoundHttpException)) {
            return response()->json(['message'=>'Page not found.', 'status'=>'error', 'error'=>true], 404);
        }else if ($exception instanceof LocalizationExtension) {
            return response()->json(['message'=>$exception->getMessage(), 'status'=>'error', 'error'=>true], $exception->getCode());
        }
        return parent::render($request, $exception);
    }
}
