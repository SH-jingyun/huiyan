<?php

namespace Core\Plugin;

Class Page {
    protected $limitStart = 0;
    protected $limitCount = 10;


    public function __construct() {
        if (isset($_POST['pageSize'])) {
            $this->limitCount = $_POST['pageSize'];
            if (isset($_POST['pageNo'])) {
                $this->limitStart = ($_POST['pageNo'] - 1) * $_POST['pageSize'];
            }
        }
    }
    
    public function __toString() {
        return $this->limitStart . ', ' . $this->limitCount;
    }
}