<?php

namespace Core;

Class LoadUrl extends Head {
    
    public function dispatch ($controllerName, $actionName) {
        if (AutoLoad::vaild($controllerName)) {
            $controller = new $controllerName($this->getServiceLocator());
            if (method_exists($controller, $actionName)) {
                return $controller->$actionName();
            } else {
                throw new \Exception(sprintf('Invaild Action %s', $actionName));//opt 跳转404
            }
        } else {
            throw new \Exception(sprintf('Invaild Controller %s', $controllerName));//opt 跳转404
        }
    }
}
