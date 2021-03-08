<?php

namespace App\Http\Controllers;

use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Http\JsonResponse;
use Illuminate\Routing\Controller as BaseController;

class Controller extends BaseController
{
    use AuthorizesRequests, DispatchesJobs, ValidatesRequests;

    /**
     * @param string $message
     * @param int $code
     * @param array $data
     * @param array $headers
     * @param int $options
     * @param int $errorCode
     * @return JsonResponse
     */
    protected function response($message = '', $code = 1, $data = [], $headers = [], $options = 0, $errorCode = 0)
    {
        if (!$data) {
            $data = new \stdClass();
        }
        $_data = [
            'success' => $code === 0 ? 0 : 1,
            'message' => $message ?: 'success',
            'data' => $data,
            'errorCode' => $errorCode,
        ];
        return new JsonResponse($_data, 200, $headers, $options);
    }
}
