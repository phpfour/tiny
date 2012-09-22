<?php

class IndexController
{
    public function index()
    {
        $view = new View();
	$view->set("abcd", "World!");
        $view->show("index");
    }
}
