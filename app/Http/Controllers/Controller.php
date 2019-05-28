<?php

namespace App\Http\Controllers;

use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;

class Controller extends BaseController
{
    use AuthorizesRequests, DispatchesJobs, ValidatesRequests;


    protected function jsonOk($data, $message = '')
    {
        header('Content-Type:application/json;charset=UTF-8');
        $return = [
            'code' => 0,
            'data' => $data,
            'message' => $message
        ];
        echo json_encode($return, JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE);
        exit;
    }

    protected function jsonErr($code, $message = '', $data = [])
    {
        header('Content-Type:application/json;charset=UTF-8');
        $return = [
            'code' => $code,
            'message' => $message,
            'data' => $data,
        ];
        echo json_encode($return, JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE);
        exit;
    }
}
