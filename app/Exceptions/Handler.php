<?php

namespace App\Exceptions;

use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use Throwable;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Symfony\Component\Routing\Exception\RouteNotFoundException;

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
        //
    }

    public function render($request,Throwable $exception){
        if($exception instanceof ModelNotFoundException){
            return response()->json([
                "error_status"=>false,
                "error"=> "Error modelo no encontrado"
            ],400);
        }

        if($exception instanceof RouteNotFoundException){
            return response()->json([
                "error_status"=>true,
                "error"=> "Api Rest protegido por AUTH 2.0, Solicita a soporte una cuenta TOKEN."
            ],401);
        }

        return parent::render($request,$exception);
    }
}
