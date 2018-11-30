<?php

namespace app\helpers;

use \Curl\Curl;

class CurlClass{
    private static $instance = null;

    public static function getInstance(){
        if(self::$instance == null)
            self::$instance = new Curl();

        return self::$instance;
    }
}