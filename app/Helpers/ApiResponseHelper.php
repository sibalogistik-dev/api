<?php

namespace App\Helpers;

class ApiResponseHelper
{
    /**
     * Generate a standardized success response.
     *
     * @param mixed $data
     * @param string $message
     * @param int $code
     * @return \Illuminate\Http\JsonResponse
     */
    public static function success($message = 'Success', $data = null, $code = 200)
    {
        return response()->json([
            'statusResp'    => true,
            'status'        => $code,
            'message'       => $message,
            'data'          => $data
        ], $code);
    }

    /**
     * Generate a standardized error response.
     *
     * @param string $message
     * @param int $code
     * @param mixed $errors
     * @return \Illuminate\Http\JsonResponse
     */
    public static function error($message = 'Error', $errors = null, $code = 400)
    {
        return response()->json([
            'statusResp'    => false,
            'status'        => $code,
            'message'       => $message,
            'errors'        => $errors
        ], $code);
    }
}
