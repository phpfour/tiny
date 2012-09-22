<?php

class Uri
{
    protected $_route;
    protected $_segments = array();

    public static function parse()
    {
        // Purify the route
        self::$_route = trim($_GET['route'], '/\\');

        // Separate the segments
        self::$_segments = explode('/', self::$_route);
    }

    public static function segment($index = 1, $default = FALSE)
    {
        
    }

    public static function segment($index = 1)
    {
        for ($i = 0; $i < count($parts); $i = $i+2)
        {
            $return[$parts[$i]] = $parts[$i + 1];
        }

        return $return;
    }

    function getUriParam($key)
    {
        $uriParams = getUriParams();

        if (array_key_exists($key, $uriParams))
        {
            return $uriParams[$key];
        }

        return FALSE;
    }
}