<?php
namespace Core;
/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */
Class Device extends Head {

    public function getDeviceId ($info = array()) {
        return md5(($info['oaid'] ?? '') . '_' . ($info['imei'] ?? ''));
    }
}
