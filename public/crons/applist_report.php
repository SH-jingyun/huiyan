<?php
// 统计用户app列表
require_once '../init.inc.php';

$sql = 'UPDATE t_app_list SET app_count = 0';
$locator->db->exec($sql);

$userId = 0;
while(true) {
    $sql = 'SELECT user_id, app_list FROM t_user WHERE user_id > ? ORDER BY user_id ASC LIMIT 5000';
    $appList = $locator->db->getAll($sql, $userId);
    if (!$appList) {
        break;
    }
    foreach ($appList as $appInfo) {
        $app = json_decode($appInfo);
//        var_dump($app);
        foreach ($app as $val) {
            $sql = 'SELECT * FROM t_app_list WHERE app_packname = ?';
            $appReport = $locator->db->getRow($sql, );
            if ($appReport) {
                $sql = 'UPDATE t_app_list SET app_count = ? WHERE app_packname = ?';
                $locator->db->exec($sql, $appReport['app_count'] + 1, );
            } else {
                $sql = 'INSERT INTO t_app_list SET app_count = ? WHERE app_packname = ?';
                $locator->db->exec($sql, 1, );
            }
        }
        $userId = $appInfo['user_id'];
    }
}
echo 'done';
