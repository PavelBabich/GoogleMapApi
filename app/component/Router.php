<?php

namespace app\component;

use const ROOT;

class Router {

    private $routes;

    public function __construct(){
        $routesPath = ROOT . '/app/config/routes.php';
        $this->routes = include($routesPath);
    }

    private function getURI(){
        if(!empty($_SERVER['REQUEST_URI'])){
            return trim($_SERVER['REQUEST_URI'], '/');
        }
    }

    public function run(){
        $uri = $this->getURI();

        foreach($this->routes as $uriPattern => $path){
            if(preg_match("~$uriPattern~", $uri)){

                $internalRoute = preg_replace("~$uriPattern~", $path, $uri);

                $segments = explode('/', $internalRoute);

                $controllerName = ucfirst(array_shift($segments)) . 'Controller';

                $actionName = array_shift($segments);

                $parametrs = $segments;

                $controllerFile = 'app\controller\\' . $controllerName;

                if(class_exists($controllerFile)){
                    $controllerObject = new $controllerFile();

                    if(method_exists($controllerObject, $actionName)){
                        $controllerObject->$actionName(...$parametrs);
                        break;
                    } else{
                        View::errorPage();
                    }
                } else{
                    View::errorPage();
                }
            }
        }
    }
}
