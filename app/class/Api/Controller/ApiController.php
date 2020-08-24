<?php

namespace Api\Controller;

use Core\Controller;

Class ApiController extends Controller {

    /**
     * 热云回调接口
     * @return false|string
     */
    public function reyunAction () {
        //channel String 渠道名 广点通，今日头条
//        imei String Android 设备 ID 866280041545123
//        appkey String 产品的唯一标示 在热云 trackingio平台生成的appkey f819f9cac5c030f812b2067d0cf8 18f7
//        skey String 生成规则: MD5(format("%s_%s_%s", activeTime,大写 appkey, securitykey)).toUpperCase Securitykey 由广告主提供
//        if (REYUN_DEBUG) {
//            //add api log
//            $logFile = LOG_DIR . 'access/' . date('Ymd') . '/';
//            if (!is_dir($logFile)) {
//                mkdir($logFile, 0755, true);
//            }
//            file_put_contents($logFile . 'access_' . date('H') . '.log', date('Y-m-d H:i:s') . '|reyun|' . json_encode($_GET) . '|' . PHP_EOL, FILE_APPEND);
//        }
        if (isset($_GET['spreadname']) && isset($_GET['imei']) && isset($_GET['appkey']) && isset($_GET['skey']) && isset($_GET['activetime'])) {
            if ('caaa89445d1922dcbba2e6e5be0896f0' != $_GET['appkey']) {
                $return = array('code' => '802', 'msg' => '验证appkey失败');
                return json_encode($return);
            }
            if (!$_GET['spreadname']) {
                $return = array('code' => '803', 'msg' => '渠道号空');
                return json_encode($return);
            }
            //securitykey：reyun_jingyun
            if (strtoupper(md5($_GET['activetime'] . '_' . strtoupper($_GET['appkey']) . '_' . 'reyun_jingyun')) != $_GET['skey']) {
                $return = array('code' => '804', 'msg' => '验证签名失败');
                return json_encode($return);
            }
//            $sql = 'SELECT user_id FROM t_user WHERE imei = ?';
//            $userId = $this->locator->db->getOne($sql, $_GET['imei']);
//            if (!$userId) {
//                $sql = 'INSERT INTO t_reyun_log SET imei = ?, app_name = ?, params = ?';
//                $this->locator->db->exec($sql, $_GET['imei'], $_GET['spreadname'], json_encode($_GET));
//                $return = array('code' => '803', 'msg' => '无效用户');
//                return json_encode($return);
//            }

            $sql = 'INSERT INTO t_reyun_log SET imei = ?, app_name = ?, params = ?, compaign_id = ?';
            $this->locator->db->exec($sql, $_GET['imei'], $_GET['spreadname'], json_encode($_GET), $_GET['_ry_adplan_id'] ?? 0);
            $logId = $this->locator->db->lastInsertId();
            $sql = 'SELECT user_id FROM t_user WHERE imei = ? OR oaid = ? OR androidid = ?';
            $userId = $this->locator->db->getOne($sql, $_GET['imei'], $_GET['imei'], $_GET['imei']);
            if ($userId) {
                $sql = 'UPDATE t_user SET reyun_app_name = ?, compaign_id = ? WHERE user_id = ?';
                $this->locator->db->exec($sql, $_GET['spreadname'], $_GET['_ry_adplan_id'] ?? 0, $userId);
                $sql = 'UPDATE t_reyun_log SET user_id = ? WHERE log_id = ?';
                $this->locator->db->exec($sql, $userId, $logId);
            }
            $return = array('code' => '200', 'msg' => '保存成功');
            return json_encode($return);
        } else {
            //code “0”:成功，“-1”:重填，“其他”:充值异 常。注意:响应 code 类型需为 String
            //msg 充值失败信息
            //orderId 推啊订单号 String
            // extParam 用户设备id，Android:imei;ios:idfa 用户id:用户唯一标识
            //{ "deviceId":"867772035090410", "userId":"123456"
            //}
            $return = array('code' => '801', 'msg' => '缺少参数');
            return json_encode($return);
        }
    }

}

