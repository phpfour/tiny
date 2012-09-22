<?php

class Registry 
{
    private static $_vars = array();
    
    public static function set($key, $var)
    {
        if (isset(self::$_vars[$key]) == true)
        {
            throw new Exception('Unable to set var `' . $key . '`. Already set.');
        }
        
        self::$_vars[$key] = $var;
        return true;
    }
    
    public static function get($key)
    {
        if (isset(self::$_vars[$key]) == false)
        {
            return null;
        }
        
        return self::$_vars[$key];
    }
    
    public static function remove($var)
    {
        unset(self::$_vars[$key]);
    }
}