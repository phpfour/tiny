<?php

class View 
{
    protected $_layout;
    protected $_basePath;
    protected $_extension = ".php";

    protected $_vars = array();

    public function __construct($basePath = null)
    {
        if (!empty($basePath)) {
            $this->_basePath = $basePath;
        } else {
            $this->_basePath = APPPATH . 'views' . DIRSEP;
        }
    }
    
    public function set($key, $value)
    {
        $this->_vars[$key] = $value;
    }

    public function __set($key, $value)
    {
        $this->set($key, $value);
    }

    public function remove($key)
    {
        unset($this->_vars[$varname]);
    }
    
    public function show($name)
    {
        $path = $this->_basePath . $name . $this->_extension;
        
        if (file_exists($path) == false) {
            throw new Exception ("View \"$name\" does not exist in folder: {$this->_basePath}", E_USER_NOTICE);
            return false;
        }

        // Load variables
        foreach ($this->_vars as $key => $value) {
            $$key = $value;
        }
        
        if (empty($this->_layout))
        {
            include ($path);
        }
        else
        {
            ob_start();
            
            include ($path);
            $content_for_layout = ob_get_contents();
            ob_end_clean();
            
            $path = APPPATH . 'views' . DIRSEP . $this->_layout . '.php';
            include ($path);
        }
    }
    
    public function get($name)
    {
        $path = $this->_basePath . $name . $this->_extension;

        if (file_exists($path) == false) {
            throw new Exception ("View \"$name\" does not exist in folder: {$this->_basePath}", E_USER_NOTICE);
            return false;
        }

        // Load variables
        foreach ($this->_vars as $key => $value) {
            $$key = $value;
        }

        ob_start();

        if (empty($this->_layout))
        {
            include ($path);
            $content = ob_get_contents();
        }
        else
        {
            include ($path);
            $content_for_layout = ob_get_contents();
            ob_end_clean();

            ob_start();
            $path = $this->_basePath . DIRSEP . $this->_layout . $this->_extension;
            include ($path);

            $content = ob_get_contents();
            ob_end_clean();
        }

        return $content;
    }
    
    public function setLayout($layout)
    {
        $this->_layout = $layout;
    }
}