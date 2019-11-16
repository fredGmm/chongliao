<?php

namespace App\Http\Controllers;

use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;

class Controller extends BaseController
{
    use AuthorizesRequests, DispatchesJobs, ValidatesRequests;

    /**
     * @return $this
     */
    public function before(){
        $method =request()->getMethod();
        if (strtolower($method) == 'options') {
            return response()->json([])->setEncodingOptions(JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE);
        }
    }

    protected function jsonOk($data, $message = '', $code = 0)
    {
        $return = [
            'code' => $code,
            'data' => $data,
            'message' => $message
        ];
        return response()->json($return)->setEncodingOptions(JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE);
//        echo json_encode($return, JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE);
    }

    protected function jsonErr($code, $message = '', $data = [])
    {
        $return = [
            'code' => $code,
            'message' => $message,
            'data' => $data,
        ];
        return response()->json($return)->setEncodingOptions(JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE);
    }

    public function callAction($method, $parameters)
    {
        $this->before();
        return call_user_func_array([$this, $method], $parameters);
    }
}
