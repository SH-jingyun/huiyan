<?php

namespace Core;

Class Controller extends Head {
    protected $userId;
    protected $posArr = array(
        1 =>  'position_1', 2 => 'position_2', 3 =>  'position_3', 4 => 'position_4', 5 =>  'position_5', 6 => 'position_6', 
        7 =>  'position_7', 8 => 'position_8', 9 =>  'position_9', 10 => 'position_10', 11 =>  'position_11', 12 => 'position_12');
    
    public function __call($name, $arguments) {
        $className = "Core\\Plugin\\" . ucfirst($name);
        $class = Autoload::vaild($className);
        if ($class) {
            $plugins = new $className();
            return $plugins->getValue($this->getServiceLocator(), $arguments[0] ?? '');
        } else {
            return FALSE;
        }
    }

    public function __get($name) {
        $className = "Core\\Plugin\\" . ucfirst($name);
        $class = Autoload::vaild($className);
        if ($class) {
            return new $className();
        } else {
            return FALSE;
        }
    }

    /**
     * 验证参数加盐加密传输
     * @return bool
     */
    protected function verifySign() {
        if (!$this->params('time') || !$this->params('sign')) {
            return FALSE;
        }
        $str = $this->params('time') . substr($this->params('time'), -6, 5);
        if ($this->params('sign') != md5($str)) {
            return FALSE;
        }
        if (abs($this->params('time') - time() * 1000) > 1000 * 60) {
            return FALSE;
        }
        return TRUE;
    }
    
}


