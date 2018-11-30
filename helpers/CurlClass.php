<?php

namespace app\helpers;

use \Curl\Curl;

class CurlClass{
    private static $instance = null;
    private static $typeGET  = 1;
    private static $typePOST = 2;

    private static function getInstance(){
        if(self::$instance == null)
            self::$instance = new Curl();

        return self::$instance;
    }
    
    public static function get($url){
        return self::request(self::$typeGET, $url);
    }

    public static function post($url, $data = []){
        return self::request(self::$typePOST, $url, $data);
    }

    private static function request($type, $url, $data = []){
        $curl = self::getInstance();        
        switch($type){
            case self::$typeGET:
                $curl->get($url);
            break;
            case self::$typePOST:
                $curl->post($url, $data);
            break;
        }
        return [
            'code'     => $curl->error ? $curl->errorCode    : 200,
            'response' => $curl->error ? $curl->errorMessage : $curl->response
        ];
    }
}