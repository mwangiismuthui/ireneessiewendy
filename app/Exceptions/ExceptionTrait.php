<?php

namespace App\Exceptions;

use Illuminate\Database\Eloquent\ModelNotFoundException;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\MethodNotAllowedHttpException;


trait ExceptionTrait{

	public function apiException($request, $exception){

		if ($this->isModel($exception)) {
                return response()->json([
                    'errors'=> 'model not found'
                ], Response::HTTP_NOT_FOUND);
            }

        if ($this->isRoute($exception)) {
            return response()->json([
                'errors'=> 'incorrect route'
            ], Response::HTTP_NOT_FOUND);
        }

        if ($this->isMethod($exception)) {
            return response()->json([
                'errors'=> 'incorrect HTTP Method'
            ], Response::HTTP_NOT_FOUND);
        }

        return parent::render($request, $exception);
	}

	public function isModel($e){
		return $e instanceof ModelNotFoundException;
	}

	public function isRoute($e){
		return $e instanceof NotFoundHttpException;
    }
    
	public function isMethod($e){
		return $e instanceof MethodNotAllowedHttpException;
	}
	// public function isSql($e){
	// 	return $e instanceof NotFoundHttpException;
	// }
}
