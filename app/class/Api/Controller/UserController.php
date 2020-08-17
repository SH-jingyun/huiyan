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
        $amountArray = array('month' => 0.1, 'quarter' => 0.2, 'forever' => 0.3);
        if (!in_array($this->params('vipType'), array_keys($amountArray))) {
            return 202;
        }
        // 添加支付信息到数据库
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
        $sql = 'INSERT INTO t_order (user_id, order_number, order_value, order_mode, order_type) SELECT :user_id, :order_number, :order_value, :order_mode, :order_type FROM DUAL WHERE NOT EXISTS (SELECT order_id FROM t_order WHERE order_number = :order_number)';
        $this->locator->db->exec($sql, array('user_id' => $this->userId, 'order_number' => $orderInfo['orderNo'], 'order_value' => $amountArray[$this->params('vipType')], 'order_mode' => $this->params('payMode'), 'order_type' => $this->params('vipType')));
        return array('orderString' => $orderInfo['orderString']);
    }

    /**
     * 接受支付宝支付成功回调
     */
    public function alipayAction () {
        $alipay = new \Core\Alipay();
        $verifyFlag = $alipay->verify();
        if ($verifyFlag) {
            $sql = 'SELECT * FROM t_order WHERE order_number = ?';
            $orderInfo = $this->locator->db->getRow($sql, $_POST['out_trade_no']);
            if ($orderInfo && ('pending' == $orderInfo['order_status'])) {
                $amountArray = array('month' => array('vip' => 2592000, 'value' => 78), 'quarter' => array('vip' => 7776000, 'value' => 88), 'forever' => array('vip' => 311040000, 'value' => 188));// 78 一个月 88 三个月 188 10年

                $status = in_array($_POST['trade_status'], array('TRADE_FINISHED', 'TRADE_SUCCESS')) ? 'success' : 'failure';
//                if ($amountArray[$orderInfo['order_type']]['value'] != $_POST['total_amount']) {
//                    $status = 'failure';
//                }
                $sql = 'UPDATE t_order SET order_status = ?, pay_data = ? WHERE order_id = ?';
                $this->locator->db->exec($sql, $status, json_encode($_POST), $orderInfo['order_id']);
                if ('success' == $status) {
                    $sql = 'SELECT vip_time FROM t_user WHERE user_id = ?';
                    $userVipTime = $this->locator->db->getOne($sql, $orderInfo['user_id']);
                    $newVipTIme = date('Y-m-d H:i:s', (('0000-00-00 00:00:00' != $userVipTime) ? strtotime($userVipTime) : time()) + $amountArray[$orderInfo['order_type']]['vip']);

                    $sql = 'UPDATE t_user SET vip_time = ? WHERE user_id = ?';
                    $this->locator->db->exec($sql, $newVipTIme, $orderInfo['user_id']);
                    //order_value
                }
                die('success');
            }
        }
        die('failure');
    }

}

