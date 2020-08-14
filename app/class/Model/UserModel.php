<?php

namespace Model;

use Core\Head;


class UserModel extends Head {
    
    /**
     * 更新用户每日首次登陆时间
     * @param type $userId
     */
    public function todayFirstLogin ($userId) {
        $sql = 'INSERT IGNORE INTO t_user_first_login SET date = ?, user_id = ?';
        $this->locator->db->exec($sql, date('Y-m-d'), $userId);
    }
         
    /**
     * 更新用户最后登陆时间
     * @param type $userId
     */
    public function lastLogin ($userId) {
        $sql = 'UPDATE t_user_ext SET last_login_time = ?, last_login_ip = ? WHERE user_id = ?';
        $this->locator->db->exec($sql, date('Y-m-d H:i:s'), $_SERVER['REMOTE_ADDR'] ?? '', $userId);
    }

    public function updateActiveTime ($userId) {
        $sql = 'UPDATE t_user_ext SET online_time = ? WHERE user_id = ?';
        $this->locator->db->exec($sql, date('Y-m-d H:i:s'), $userId);
    }

    public function updateUserGold ($userId, $gold) {
        $sql = 'UPDATE t_user_ext SET user_gold = ? WHERE user_id = ?';
        $this->locator->db->exec($sql, $gold, $userId);
    }
}
