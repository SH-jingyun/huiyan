<?php


namespace Core\Plugin;

Class Params {
    
    public function getValue ($locator, $name) {
        $params = $locator->params;
        if ($name) {
            return $params[$name] ?? '';
        }
        return $params;
    }
}

