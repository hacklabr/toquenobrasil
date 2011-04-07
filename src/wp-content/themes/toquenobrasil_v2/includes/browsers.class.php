<?php
class Browsers{
    public static function isIE9(){
        return self::isIE(9);
    }
    
    public static function isIE8(){
        return self::isIE(8);
    }
    
    public static function isIE7(){
        return self::isIE(7);
    }
    
    
    public static function isIE($version = ''){
        return is_int(strpos($_SERVER['HTTP_USER_AGENT'], "MSIE $version"));
    }
}