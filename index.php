<?php

define('ROOT', dirname(__FILE__));

require_once(ROOT.'/component/Router.php');

$router = new Router();
$router->run();