<?php
// 统计用户app列表
require_once __DIR__ . '/../init.inc.php';

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
        if ($appInfo['app_list']) {
            $app = json_decode($appInfo['app_list']);
//            var_dump($app);
//            exit;
            foreach ($app as $val) {
                $sql = 'SELECT * FROM t_app_list WHERE app_packname = ?';
                $appReport = $locator->db->getRow($sql, $val->packageName);
                if ($appReport) {
                    $sql = 'UPDATE t_app_list SET app_count = ? WHERE app_packname = ?';
                    $locator->db->exec($sql, $appReport['app_count'] + 1, $val->packageName);
                } else {
                    $sql = 'INSERT INTO t_app_list SET app_count = ?, app_packname = ?, app_name = ?';
                    $locator->db->exec($sql, 1, $val->packageName, $val->appName);
                }
            }
        }
        $userId = $appInfo['user_id'];
    }
}
echo 'done';
