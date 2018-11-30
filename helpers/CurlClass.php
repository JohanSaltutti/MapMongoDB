<?php

namespace app\helpers;

use \Curl\Curl;

class CurlClass{
    private static $instance = null;

    private static function getInstance(){
        if(self::$instance == null)
            self::$instance = new Curl();

        return self::$instance;
    }

    public static function get($url){
        $curl = self::getInstance();
        $curl->get($url);
        return [
            'code'     => $curl->error ? $curl->errorCode    : 200,
            'response' => $curl->error ? $curl->errorMessage : $curl->response,
        ];
    }
}