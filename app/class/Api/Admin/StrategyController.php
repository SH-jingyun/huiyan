<?php

namespace Api\Admin;

use Core\Controller;

Class StrategyController extends Controller {

    // postion列表
    public function listAction() {
        $whereArr = array('1 = 1');
        $dataArr = array();

//        if (isset($_POST['user_id']) && $_POST['user_id']) {
//            $whereArr[] = 'user_id = :user_id';
//            $dataArr['user_id'] = $_POST['user_id'];
//        }
        $where = 'WHERE ' . implode(' AND ', $whereArr);

        $sql = "SELECT COUNT(*) FROM ad_strategy " . $where;
        $totalCount = $this->locator->db->getOne($sql, $dataArr);
        $list = array();
        if ($totalCount) {
            $sql = "SELECT s.strategy_id, s.strategy_name, s.create_time, a.app_name, p.position_name, t.position_type_name, u.group_name FROM ad_strategy s LEFT JOIN ad_position p USING(position_id) LEFT JOIN ad_user_group u USING (group_id) LEFT JOIN ad_app a USING(app_id) LEFT JOIN ad_position_type t USING(position_type_id) " . $where;
            $list = $this->locator->db->getAll($sql, $dataArr);
        }
        return array(
            'totalCount' => (int) $totalCount,
            'list' => $list,
        );
    }

    public function detailsAction () {

        $detailsArr = array('details' => array());

        $sql = 'SELECT position_id id, CONCAT(app_name, "--", position_name) name FROM ad_position LEFT JOIN ad_app USING(app_id)';
        $detailsArr['position'] = $this->locator->db->getAll($sql);

        $sql = 'SELECT group_id id, group_name name FROM ad_user_group';
        $detailsArr['user'] = $this->locator->db->getAll($sql);

        if (isset($_POST['id'])) {
            $sql = 'SELECT strategy_id, strategy_name, position_id, group_id FROM ad_strategy WHERE strategy_id = ?';
            $detailsArr['details'] = $this->locator->db->getRow($sql, $_POST['id']);
        }
        return $detailsArr;
    }
}


