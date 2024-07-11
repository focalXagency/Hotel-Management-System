<?php

namespace App\Http\Traits;


use Illuminate\Http\JsonResponse;

trait ApiResponserTrait{

    protected function successResponse(? array $data=[], string $message,int $httpResponseCode = 200): JsonResponse
    {
        return response()->json([
            'success'    => true,
            'message'    => $message ?? null,
            'data'       => $data ?? null ,
            'errors'     => null,
        ], $httpResponseCode);
    } 

    protected function errorResponse(string $message, ? array $errors = [], int $httpResponseCode = 401): JsonResponse {
        return response()->json([
            'success'    => false,
            'message'    => $message ?? null,
            'data'       => null,
            'errors'     => $errors ?? null,
        ], $httpResponseCode);
    }
    
    public function notFound($message){

        return response()->json([
            'success'  =>false,
            'message'  =>$message,
            'code'     =>404
        ]);
    }

    protected function apiResponse($success,$message,$data=[],$statusCode=200){
        return response()->json([
            'success' => $success,
            'message' => $message,
            'data'=> $data,
        ],$statusCode);
    }
    
    protected function successResponseTest($message='success',$data=[],$statusCode=200){
        return $this->apiResponse(true,$message,$data,$statusCode);
    }

    protected function errorResponseTest($message='Invalid',$statusCode=500){
        return $this->apiResponse(false,$message,null,$statusCode);
    }

}

