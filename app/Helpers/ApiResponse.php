<?php

namespace App\Helpers;

class ApiResponse
{
    public static function success($data = [], $message = null, $status = 200)
    {
        return response()->json([
            'success' => true,
            'message' => $message,
            'data'    => $data,
        ], $status);
    }

    public static function error($message = null, $status = 400, $errors = [])
    {
        return response()->json([
            'success' => false,
            'message' => $message,
            'errors'  => $errors,
        ], $status);
    }

    public static function paginated($paginator, $message = null)
    {
        return response()->json([
            'success' => true,
            'message' => $message,
            'data' => [
                'result' => $paginator->items(),
                'pagination' => [
                    'total'        => $paginator->total(),
                    'per_page'     => $paginator->perPage(),
                    'current_page' => $paginator->currentPage(),
                    'last_page'    => $paginator->lastPage(),
                ],
            ],
        ]);
    }
}
