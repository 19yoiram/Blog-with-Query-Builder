<?php

namespace App\Traits;

trait ApiResponseTrait
{
    //
     public function successResponse($message = '', $data = null, $status = 200)
    {
        return response()->json([
            'success' => true,
            'message' => $message,
            'data' => $data,
        ], $status);
    }

    public function errorResponse($message = 'Something went wrong', $status = 400)
    {
        return response()->json([
            'success' => false,
            'message' => $message,
        ], $status);
    }

     public function notFoundResponse($message = 'Data not found')
    {
        return response()->json([
            'success' => false,
            'message' => $message,
        ], 404);
    }
}
