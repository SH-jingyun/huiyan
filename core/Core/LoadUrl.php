<?php

namespace Core;

Class LoadUrl extends Head {
    
    public function dispatch ($controllerName, $actionName) {
        if (AutoLoad::vaild($controllerName)) {
            $controller = new $controllerName($this->getServiceLocator());
            if (method_exists($controller, $actionName)) {
                return $controller->$actionName();
            } else {
                http_response_code(404);
                exit;
            }
        } else {
            http_response_code(404);
            exit;
        }
    }
}
