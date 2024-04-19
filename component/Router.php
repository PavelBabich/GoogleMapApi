<?php

class Router {
    private $routes;

    public function __construct() {
        $routesPath = ROOT . '/config/routes.php';
        $this->routes = include($routesPath);
    }

    private function getURI() {
        if (!empty($_SERVER['REQUEST_URI'])) {
            return trim($_SERVER['REQUEST_URI'], '/');
        }
    }

    public function run() {
        $uri = $this->getURI();

        foreach ($this->routes as $uriPattern => $path) {
            if (preg_match("~$uriPattern~", $uri)) {

                $internalRoute = preg_replace("~$uriPattern~", $path, $uri);

                $segments = explode('/', $internalRoute);

                $controllerName = ucfirst(array_shift($segments)) . 'Controller';

                $actionName = array_shift($segments);

                $parametrs = $segments;

                $controllerFile = ROOT . '/controller/' . $controllerName . '.php';

                if (file_exists($controllerFile)) {
                    require_once($controllerFile);
                    $controllerObject = new $controllerName;

                    if (method_exists($controllerObject, $actionName)) {
                        $result = $controllerObject->$actionName(...$parametrs);
                    } else {
                        require_once(ROOT . '/views/site/404.php');
                        exit;
                    }
                } else {
                    require_once(ROOT . '/views/site/404.php');
                    exit;
                }

                if ($result != null) {
                    break;
                }
            }
        }
    }
}
