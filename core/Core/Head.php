<?php
namespace Core;
/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */
Class Head {
    protected $locator;
    
    public function __construct(ServiceLocator $locator) {
        $this->setServiceLocator($locator);
    }
    
    public function setServiceLocator(ServiceLocator $serviceLocator) {
        $this->locator = $serviceLocator;
    }
    
    public function getServiceLocator() {
        return $this->locator;
    }
}
