<?php

namespace Api\Controller;

use Core\Controller;

Class UserController extends Controller {

    /**
     * 获取用户信息
     * @return array|int
     */
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

    /**
     * 获取订单号
     */
    public function orderAction () {
        $token = $_SERVER['HTTP_ACCESSTOKEN'] ?? '';
        if ($token) {
            $sql = 'SELECT user_id FROM t_user WHERE access_token = ?';
            $this->userId = $this->locator->db->getOne($sql, $token);
        }
        if (!$this->userId) {
            return 201;
        }
        $amountArray = array('month' => 78, 'quarter' => 188, 'forever' => 298);
        if (!in_array($this->params('vipType'), array_keys($amountArray))) {
            return 202;
        }
        switch ($this->params('payMode')) {
            case 'alipay':
                $alipay = new \Core\Alipay();
                $orderInfo = $alipay->unifiedorder($amountArray[$this->params('vipType')]);
                break;
            case 'wxpay':
                return 202;
                break;
            default :
                return 202;
        }
        return array('orderString' => $orderInfo['orderString']);
    }

}

