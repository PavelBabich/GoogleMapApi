<?php

use app\component\Router;

define('ROOT', dirname(__FILE__));

//autoload classes
spl_autoload_register(function($class) {
    $path = str_replace('\\', '/', $class.'.php');
    if (file_exists($path)) {
        require $path;
    }
});

$router = new Router();
$router->run();