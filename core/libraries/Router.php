<?php

class Router 
{
    protected $_path;
    
    protected $_defaultInit = 'init';
    protected $_defaultAction = 'index';
    protected $_defaultController = 'index';
    
    public function setPath($path)
    {
        $path .= DIRSEP;
        
        if (is_dir($path) == false) {
            throw new Exception ("Invalid controller path: $path");
        }
        
        $this->_path = $path;
    }
    
    public function delegate()
    {
        // Analyze route
        $this->_getController($file, $controller, $action, $args);

        // File available?
        if (is_readable($file) == false) {
            die ('404 Not Found');
        }

        // Include the file
        include ($file);

        // Initiate the class
        $class = ucfirst($controller) . 'Controller';
        $controller = new $class();
        
        // Action available?
        if (is_callable(array($controller, $action)) == false) {
            die ('404 Not Found');
        }
        
        // Init Action available?
        if (is_callable(array($controller, $this->_defaultInit)) == true)
        {
            call_user_func(array($controller, $this->_defaultInit));
        }

        // Run action
        call_user_func_array(array($controller, $action), $args);
    }

    private function _getController(&$file, &$controller, &$action, &$args)
    {
        $route = (empty($_GET['route'])) ? $this->_defaultController : $_GET['route'];

        // Get separate parts
        $route = trim($route, '/\\');
        $parts = explode('/', $route);

        // Find right controller
        $lastPath = $this->_path;

        foreach ($parts as $part) {
            $fullPath = $lastPath . $part;

            // Is there a dir with this path?
            if (is_dir($fullPath)) {
                $lastPath .= $part . DIRSEP;
                array_shift($parts);
                continue;
            }

            // Find the file
            if (is_file($fullPath . '.php'))
            {
                $controller = $part;
                array_shift($parts);
                break;
            }
        }

        if (empty($controller)) {
            $controller = $this->_defaultController;
        }

        // Get action
        $action = array_shift($parts);

        if (empty($action)) {
            $action = $this->_defaultAction;
        }

        $file = $lastPath . $controller . '.php';
        $args = $parts;
    }
    
}
