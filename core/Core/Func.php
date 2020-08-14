<?php
namespace Core;

Class Func {
    
    public static function getParams () {
        $params = array();
        $requestUrl = strtok($_SERVER['REQUEST_URI'], '?');
        // remove the base path
        $requestUrl = substr($requestUrl, strlen('/'));
        // raw urldecode
        $requestUrl = rawurldecode($requestUrl);

        $routerArr = explode('/', $requestUrl);
        
        switch (count($routerArr)) {
            //admin
            case 3:
                if ('admin' == $routerArr[0]) {
                    $controller = 'Api\\Admin\\' . ucfirst($routerArr[1]) . 'Controller';
                    $action = $routerArr[2] . 'Action';
                    $type = 'admin';
                } else {
                    throw new \Exception(sprintf('Error url %s', $requestUrl));//opt 跳转404
                }
                break;
            //api
            case 2:
                $controller = 'Api\\Controller\\' . ucfirst($routerArr[0]) . 'Controller';
                $action = $routerArr[1] . 'Action';
                $type = 'api';
//                var_dump(file_get_contents("php://input"));
//                var_dump(json_decode(file_get_contents("php://input"), TRUE));
                $params = json_decode(file_get_contents("php://input"), TRUE) ?: array();
                break;
            default:
                throw new \Exception(sprintf('Error url %s', $requestUrl));//opt 跳转404
        }
        return array_merge($params, array(
            '_controller' => $controller,
            '_action' => $action,
            '_type' => $type
        ));
    }
    
    public static function getDb () {
        $db = new NewPdo('mysql:dbname=' . DB_DATABASE . ';host=' . DB_HOST . ';port=' . DB_PORT, DB_USERNAME, DB_PASSWORD);
        $db->exec("SET time_zone = '+8:00'");
        $db->setAttribute(\PDO::ATTR_DEFAULT_FETCH_MODE, \PDO::FETCH_ASSOC);
        return $db;
    }

    public static function getDbWalks () {
        $db = new NewPdo('mysql:dbname=' . DB_DATABASE_WALKS . ';host=' . DB_HOST_WALKS . ';port=' . DB_PORT_WALKS, DB_USERNAME_WALKS, DB_PASSWORD_WALKS);
        $db->exec("SET time_zone = '+8:00'");
        $db->setAttribute(\PDO::ATTR_DEFAULT_FETCH_MODE, \PDO::FETCH_ASSOC);
        return $db;
    }

    public static function getRedis () {
        $redis = new \Redis();
        $redis->pconnect('redis', 6379);
        $redis->select(1);
        $redis->setOption(\Redis::OPT_SERIALIZER, \Redis::SERIALIZER_PHP);
        return $redis;
    }
}

