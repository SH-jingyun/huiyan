<?php
namespace Core;

Class ServiceLocator {
    protected $services = array();
    protected $servicesConfig = array();
    
    public function __construct ($config = array()) {
        $this->servicesConfig = $config;
    }
    
    public function __get ($name) {
        $name = ucfirst($name);
        if(isset($this->servicesConfig[$name])) {
            return $this->get($name);
        }
        
        throw new \Exception(sprintf('Can\'t find services : %s', $name));
    }
    
    public function get ($name) {
        if (isset($this->services[$name])) {
            return $this->services[$name];
        }
        
        $value = $this->servicesConfig[$name];
        if (is_string($value)) {
            if (class_exists($value)) {
                $this->services[$name] = new $value($this);
            } else {
                $this->services[$name] = $value;
            }
        } elseif (is_callable($value)) {
            $this->services[$name] = $value($this);
        } else {
            throw new \Exception(sprintf('Not a valid services : %s', $name));
        }
        return $this->services[$name];
    }
}
