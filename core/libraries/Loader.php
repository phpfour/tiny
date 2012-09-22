<?php

class Loader
{
    public static function autoload($class)
    {
        if (class_exists($class, false) || interface_exists($class, false)) {
            return;
        }

        // Autodiscover the path from the class name
        $file = str_replace('_', DIRECTORY_SEPARATOR, $class) . '.php';
        
        include $file;

        if (!class_exists($class, false) && !interface_exists($class, false)) {
            throw new Exception("File \"$file\" does not exist or class \"$class\" was not found in the file");
        }
    }
}