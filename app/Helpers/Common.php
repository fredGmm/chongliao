<?php
/**
 * Created by PhpStorm.
 * User: fredgui
 * Date: 2019/5/28
 * Time: 15:01
 */
namespace App\Helpers;

class Common{

    public static function curl($url, $method='GET', $param = '', $timeout = 50, $cookie = '', $decode = 1){
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_HEADER, 0);
        if ($param) {
            curl_setopt($ch, CURLOPT_POSTFIELDS, $param);
        }
        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, $method);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_BINARYTRANSFER, 1);
        curl_setopt($ch, CURLOPT_TIMEOUT, $timeout);

        if ($cookie) {
            curl_setopt($ch, CURLOPT_COOKIE, $cookie);
        }
        $data = curl_exec($ch);
        $httpCode = (int)curl_getinfo($ch, CURLINFO_HTTP_CODE);

        if ($httpCode >= 200 && $httpCode <= 299) {
            if ($decode == 1) {
                try {
                    $json_data = json_decode($data, true);
                    $data = !is_null($json_data) ?  $json_data : $data;
                }catch (\RuntimeException $e){

                }
            }
        } else {
            $data = null;
        }
        curl_close($ch);
        return $data;
    }
}