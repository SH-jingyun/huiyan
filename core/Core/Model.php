<?php

namespace Core;

Class Model extends Head {
    
    public function __get($name) {
        $className = 'Model\\' . ucfirst($name) . 'Model';
        return new $className($this->locator);
    }
    
}


