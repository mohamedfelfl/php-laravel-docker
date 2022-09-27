<?php

namespace App\Traits;

use Illuminate\Http\JsonResponse;

trait response
{


    protected function jsonResponseMessage($message, $success = true, $data = null): JsonResponse
    {
        /*$json = json_encode([
            'success' => $success,
            'code' => $code,
            'message' => $message,
            'data' => $data
        ]);*/
        return response()->json([
            'success' => $success,
            'message' => $message,
            'data' => $data
        ]);
        //return response()->json($json);
    }
}
