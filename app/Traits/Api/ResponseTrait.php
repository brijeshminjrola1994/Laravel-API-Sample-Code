<?php

namespace App\Traits\Api;

use Illuminate\Http\Response;
use Illuminate\Support\Facades\Log;

trait ResponseTrait
{

    /**
     * success response method.
     *
     * @return \Illuminate\Http\Response
     */
    public function sendSuccessResponse($result, $message)
    {
        $response = [
            'status' => true,
            'message' => $message,
            'errors' => [],
            'data' => $result,
        ];
        return response()->json($response, $code = Response::HTTP_OK);
    }

    /**
     * return error response.
     *
     * @return \Illuminate\Http\Response
     */
    public function sendErrorResponse($error, $errorMessages = [], $code = Response::HTTP_NOT_FOUND)
    {
        $response = [
            'status' => false,
            'message' => $error,
            'data' => [],
        ];
        $response['errors'] = $errorMessages;

        return response()->json($response, $code);
    }
}
