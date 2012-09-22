<?php

abstract Class Controller
{
    protected $registry;
    protected $view;
    
    function __construct($registry = null)
    {
        if (!is_null($registry))
        {
            //$this->registry = $registry;
            //$this->view = $registry->get('view');
        }
    }
    
    abstract function index();

    function init()
    {
        // Optionally can be overwridden
        // Will be called automatically by constructor
    }
}