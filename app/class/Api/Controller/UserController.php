<?php

namespace Api\Controller;

use Core\Controller;

Class UserController extends Controller {

    public function infoAction () {
        if (!$this->params('oaid') && !$this->params('imei')) {
            return 202;
        }

        $deviceClass = new \Core\Device($this->locator);
        $deviceId = $deviceClass->getDeviceId($this->params());

        $sql = 'SELECT * FROM t_user WHERE device_id = ?';
        $userInfo = $this->locator->db->getRow($sql, $deviceId);
        if ($userInfo) {
            return array('isVip' => (strtotime($userInfo['vip_time']) > time()) ? 1 : 0, 'userStatus' => $userInfo['user_status'], 'accessToken' => $userInfo['access_token']);
        } else {
            $accessToken = md5($deviceId . time());

            $sql = 'INSERT INTO t_user (user_source, device_id, access_token, oaid, imei, mac, brand, model) SELECT :user_source, :device_id, :access_token, :oaid, :imei, :mac, :brand, :model FROM DUAL WHERE NOT EXISTS (SELECT user_id FROM t_user WHERE device_id = :device_id)';
            $this->locator->db->exec($sql, array('user_source' => $this->params('source'), 'device_id' => $deviceId, 'access_token' => $accessToken, 'oaid' => $this->params('oaid'), 'imei' => $this->params('imei'), 'mac' => $this->params('mac'), 'brand' => $this->params('brand'), 'model' => $this->params('model')));
            return array('isVip' => 0, 'userStatus' => 1, 'accessToken' => $accessToken);
        }
    }
}

