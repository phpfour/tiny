<?php

function __autoload($className) 
{
    $filename = strtolower($className) . '.php';
    $file = BASEPATH . 'libraries' . DIRSEP . $filename;
    
    if (file_exists($file) == false) {
        $file = APPPATH . 'libraries' . DIRSEP . $filename;
        if (file_exists($file) == false) {
            return false;
        }
    }
    
    include ($file);
}

function siteUrl()
{
    $config = $registry->get('config');
    return $config['siteUrl'];
}

function getUriParams()
{
    $route = (empty($_GET['route'])) ? '' : $_GET['route'];
    $route = trim($route, '/\\');
    $parts = explode('/', $route);
    
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

function dbConnect()
{
    global $registry;
    $dbInfo = $registry->get('dbInfo');

    $dsn  = "mysql:host=" . $dbInfo['hostname'] . ";";
    $dsn .= "port=" . $dbInfo['port'] . ";";
    $dsn .= "dbname=" . $dbInfo['database'];

    $db = new PDO($dsn, $dbInfo['username'], $dbInfo['password']);
    return $db;
}

/**
 * Group a multidimentional array by $groupBy and returns as tow dimentional.
 *
 * @param array  $array
 * @param string $groupBy
 */
function groupArray($array, $groupBy = 'id')
{
    if((!is_array($array)) && (! $array instanceof ArrayIterator) ){
        return false;
    }
    
    $groupdArray = array();
    foreach ($array as $element){
        $groupdArray[$element[$groupBy]][] = $element; 
    }

    return $groupdArray;
}

function redirect($url)
{
    if(!headers_sent()){
        header("Location:".$url);
        exit;
    }
}