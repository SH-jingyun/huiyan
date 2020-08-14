<?php

namespace Api\Admin;

use Core\Controller;

Class PosController extends Controller {

    // postion列表
    public function listAction() {
        if (isset($_POST['action'])) {
            if ($_POST['position_id']) {

            }
        }
        $whereArr = array('1 = 1');
        $dataArr = array();

//        if (isset($_POST['user_id']) && $_POST['user_id']) {
//            $whereArr[] = 'user_id = :user_id';
//            $dataArr['user_id'] = $_POST['user_id'];
//        }
        $where = 'WHERE ' . implode(' AND ', $whereArr);

        $sql = "SELECT COUNT(*) FROM ad_position p " . $where;
        $totalCount = $this->locator->db->getOne($sql, $dataArr);
        $list = array();
        if ($totalCount) {
            $sql = "SELECT a.app_name, p.position_id, p.position_name, t.position_type_name, p.position_status, p.position_remark, p.create_time FROM ad_position p LEFT JOIN ad_position_type t USING(position_type_id) LEFT JOIN ad_app a USING(app_id) $where ORDER BY p.create_time DESC LIMIT " . $this->page;
            $list = $this->locator->db->getAll($sql, $dataArr);
        }
        $sql = 'SELECT app_id id, app_name name FROM ad_app';
        $appList = $this->locator->db->getAll($sql);

        $sql = 'SELECT position_type_id id, position_type_name name FROM ad_position_type';
        $typeList = $this->locator->db->getAll($sql);
        return array(
            'totalCount' => (int) $totalCount,
            'list' => $list,
            'app' => $appList,
            'type' => $typeList,
        );
    }

    public function detailsAction () {
        $activityInfo = array();
        if (isset($_POST['action']) && 'edit' == $_POST['action']) {
            if ($_POST['id']) {
                $sql = 'UPDATE ad_position SET app_id = ?, position_name = ?, position_status = ?, position_type_id = ?, position_remark = ? WHERE position_id = ?';
                $this->locator->db->exec($sql, $_POST['app_id'] ?? 0,  $_POST['position_name'] ?? '',  $_POST['position_status'] ?? 0,  $_POST['position_type_id'] ?? 0,  $_POST['position_remark'] ?? '',  $_POST['id']);
            } else {
                $sql = 'INSERT INTO ad_position SET app_id = ?, position_name = ?, position_status = ?, position_type_id = ?, position_remark = ?';
                $this->locator->db->exec($sql, $_POST['app_id'] ?? 0,  $_POST['position_name'] ?? '',  $_POST['position_status'] ?? 0,  $_POST['position_type_id'] ?? 0,  $_POST['position_remark'] ?? '');
            }
            return array();
        }
        if (isset($_POST['pos_id'])) {
            $sql = "SELECT * FROM ad_position WHERE position_id = ?";
            $activityInfo = $this->locator->db->getRow($sql, $_POST['pos_id']);
        }
        return $activityInfo;
    }
}

