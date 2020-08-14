<?php

namespace Api\Admin;

use Core\Controller;

Class BaseController extends Controller {

    public function loginAction() {  
        if (isset($_POST['username']) && isset($_POST['password'])) {
            if (JY_WALK_ADMIN_USER == $_POST['username']) {
                $verifyPass = $_POST['password'];
                if (md5(JY_WALK_ADMIN_PASSWORD) == $_POST['password']) {
                    return array();
                }
            }
        }
//        $this->mode = 'POST';
//        $sql = 'SELECT * FROM t_order LIMIT 1';
//        var_dump($this->db->getRow($sql));
//  data: {
//    ticket: 'ticket',
//    token: '11111',
//  },
//  msg: '操作成功',
//  status: 1,
        throw new \Exception("Login failure");
    }

    public function menuAction() {
//    list: [
//      {
//        id: 600110233,
//        resName: '图表',
//        resKey: 'echarts',
//        resIcon: 'statistics',
//      },
//
//      {
//        id: 10062,
//        resName: '设置中心',
//        children: [
//          {
//            id: 10108,
//            resName: '用户管理',
//            resKey: 'set$/userManage',
//            resIcon: 'userManage',
//          },
//          {
//            id: 10109,
//            resName: '角色管理',
//            resKey: 'set$/roleManage',
//            resIcon: 'roleManage',
//          },
//          {
//            id: 10110,
//            resName: '权限管理',
//            resKey: 'set$/moduleManage',
//            resIcon: 'moduleManage',
//          },
//        ],
//        resKey: 'set$',
//        resIcon: 'xtxg',
//      },
//    ],
        return  array('list' => array(
            array('id' => '1', 'resName' => '计步宝', 'resKey'=> 'menu_stepcounter', 'resIcon'=> 'xtxg', 'children' => array(
                array( 'resName' => '首页', 'resKey'=> 'index'),
                array( 'resName' => '运营位管理', 'resKey'=> 'ad'),
                array( 'id' => '1-1', 'resName' => '用户管理', 'resKey'=> 'menu_stepcounter_user', 'children' => array(
                    array( 'resName' => '用户明细', 'resKey'=> 'user'),
                    array( 'resName' => '用户提现', 'resKey'=> 'withdraw'),
                    array( 'resName' => '用户反馈', 'resKey'=> 'feedback'),
                    array( 'resName' => '用户邀请', 'resKey'=> 'invited'),
                )),
                array( 'id' => '1-2', 'resName' => '系统管理', 'resKey'=> 'menu_stepcounter_system', 'children' => array(
                    array( 'resName' => '版本升级', 'resKey'=> 'version'),
                    array( 'resName' => '广告频闭', 'resKey'=> 'version-ad'),
                    array( 'resName' => '三方错误码', 'resKey'=> 'sdk-error'),
                )),
            )),
            array('id' => '2', 'resName' => '狗狗世界', 'resKey'=> 'menu_dogsworld', 'resIcon'=> 'moduleManage', 'children' => array(
                array( 'id' => '2-1', 'resName' => '用户管理', 'resKey'=> 'menu_dogsworld_user', 'children' => array(
                    array( 'resName' => '内部用户', 'resKey'=> 'dogs-interior'),
                    array( 'resName' => '用户提现', 'resKey'=> 'dogs-withdraw'),
                    array( 'resName' => '用户列表', 'resKey'=> 'dogs-user'),
                )),
                array( 'id' => '2-2', 'resName' => '系统管理', 'resKey'=> 'menu_dogsworld_system', 'children' => array(
                    array( 'resName' => '版本升级', 'resKey'=> 'dogs-version'),
                    array( 'resName' => '广告频闭', 'resKey'=> 'dogs-version-ad'),
                )),
            )),
            array('id' => '4', 'resName' => '走路多多', 'resKey'=> 'menu_zou', 'resIcon'=> 'xtxg', 'children' => array(
                array( 'resName' => '首页', 'resKey'=> 'zou-index'),
                array( 'resName' => '运营位管理', 'resKey'=> 'zou-ad'),
                array( 'id' => '4-1', 'resName' => '用户管理', 'resKey'=> 'menu_stepcounter_user', 'children' => array(
                    array( 'resName' => '用户明细', 'resKey'=> 'zou-user'),
                    array( 'resName' => '用户提现', 'resKey'=> 'zou-withdraw'),
                    array( 'resName' => '用户反馈', 'resKey'=> 'zou-feedback'),
                    array( 'resName' => '用户邀请', 'resKey'=> 'zou-invited'),
                )),
                array( 'id' => '4-2', 'resName' => '系统管理', 'resKey'=> 'menu_stepcounter_system', 'children' => array(
                    array( 'resName' => '版本升级', 'resKey'=> 'zou-version'),
                    array( 'resName' => '广告频闭', 'resKey'=> 'zou-version-ad'),
                    array( 'resName' => '三方错误码', 'resKey'=> 'zou-sdk-error'),
                )),
            )),
            array('id' => '3', 'resName' => '广告配置', 'resKey'=> 'menu_ad', 'resIcon'=> 'xtxg', 'children' => array(
                array( 'resName' => '开发中', 'resKey'=> 'ad_waiting'),
//                array( 'resName' => '应用列表', 'resKey'=> 'ad-app'),
//                array( 'resName' => '广告位管理', 'resKey'=> 'ad-position'),
//                array( 'resName' => '用户分群管理', 'resKey'=> 'ad-user-group'),
//                array( 'resName' => '广告策略管理', 'resKey'=> 'ad-strategy'),
//                array( 'resName' => '广告代码位管理', 'resKey'=> 'ad-strategy-details/1'),
            )),
        ));
    }

    public function userInfoAction () {
//  data: {
//    id: 1,
//    username: 'admin',
//    password: '121212',
//    chineseName: '管理员',
//    idcardNo: '000000000000000000',
//    policeCode: '000000',
//    deptCode: '370200000000',
//    gender: 1,
//    email: 'abc@abc.com',
//    phoneNo: '15100000005',
//    duty: '超级管理员',
//    address: 'address',
//    remark: 'remarl',
//    type: 0,
//    status: 0,
//    roles: [
//      {
//        id: 1,
//        roleName: '超级管理员',
//        resources: [],
//      },
//    ],
//    deptName: '杭州市',
//    ticket: '.2XxGlEuidOmAoYIdSo6pQIlGbQSh83U7p4eJsoTO-70',
//    gxdwdm: '370200000000',
//    deptLevel: '1',
//    defaultDeptCode: '370200000000',
//    defaultXzqhCode: '370200',
//  }
        return array('id' => 1);
    }

    public function logoutAction() {
        return array();
    }


    public function uploadAction() {
        header('Access-Control-Allow-Headers:x-requested-with');
        if ($_FILES) {
            $uploadFile = $_FILES['file'];
            switch ($uploadFile['type']) {
                case 'image/png':
                case 'image/jpg':
                case 'image/gif':
                case 'image/jfif':
                case 'image/jpeg':
                    if (isset($_POST['action'])) {
                        switch ($_POST['action']) {
                            case 'interior':
                                $dir = INTERIOR_DIR;
                                break;
                        }
                    } else {
                        return false;
                    }
                    if (!is_dir($dir)) {
                        mkdir($dir, 0755);
                    }
                    $result = move_uploaded_file($uploadFile['tmp_name'], $dir . $uploadFile['name']);
                    break;
                case 'application/vnd.android.package-archive':
                    if (!is_dir(APK_DIR)) {
                        mkdir(APK_DIR, 0755);
                    }
                    $result = move_uploaded_file($uploadFile['tmp_name'], APK_DIR . $uploadFile['name']);
                    break;
            }
            return array($_FILES);
        }
    }
}

