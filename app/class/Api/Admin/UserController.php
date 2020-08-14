<?php

namespace Api\Admin;

use Core\Controller;

Class UserController extends Controller {

    // postion列表
    public function groupAction() {
        $whereArr = array('1 = 1');
        $dataArr = array();

//        if (isset($_POST['user_id']) && $_POST['user_id']) {
//            $whereArr[] = 'user_id = :user_id';
//            $dataArr['user_id'] = $_POST['user_id'];
//        }
        $where = 'WHERE ' . implode(' AND ', $whereArr);

        $sql = "SELECT COUNT(*) FROM ad_user_group " . $where;
        $totalCount = $this->locator->db->getOne($sql, $dataArr);
        $list = array();
        if ($totalCount) {
            $sql = "SELECT * FROM ad_user_group " . $where;
            $list = $this->locator->db->getAll($sql, $dataArr);
        }
        return array(
            'totalCount' => (int) $totalCount,
            'list' => $list
        );
    }
}

