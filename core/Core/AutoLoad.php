<?php

namespace Core;
/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

class AutoLoad {
    protected static $namespaces = array(
        'Api' => API_DIR,
        'Core' => CORE_DIR,
        'Model' => MODEL_DIR
    );


    public static function load ($className, $suffix = '.php') {
        $namespaces = self::$namespaces;
        
        $trunk = strtr($className, array('_' => '/', '\\' => '/'));
        $prefix = strstr($trunk, '/', true);
        
        if (in_array($prefix, array_keys($namespaces))) {
            $file = $namespaces[$prefix] . $trunk . $suffix;
            if (file_exists($file)) {        
                require_once $file;
                return true;
            }
        }
        return false;
    }
    
    public static function vaild ($className, $suffix = '.php') {
        $namespaces = self::$namespaces;
        
        $trunk = strtr($className, array('_' => '/', '\\' => '/'));
        $prefix = strstr($trunk, '/', true);
        
        if (in_array($prefix, array_keys($namespaces))) {
            $file = $namespaces[$prefix] . $trunk . $suffix;
            if (file_exists($file)) {
                return true;
            }
        }
        return false;
    }

    /**
     * register autoload
     */
    public static function register()
    {
        spl_autoload_register(array('static', 'load'));
    }
}