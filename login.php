<?php

/**
 * 登陆页
 * 
 * @copyright (c) 2013, mMonitor.org
 * @version 1.0
 * @author Actrace
 * @date 2013-10-20 10:55:48
 */
//载入一些玩意
include_once 'include/init.php';
include_once 'include/init_view.php';
include_once 'include/init_user.php';
include_once 'include/init_lang.php';
include_once 'include/init_install.php';

//动作控制
$str_action = isset($_GET['action']) ? $_GET['action'] : '';
//过渡页模板填充
$obj_view->sign('_TPL_PL', $obj_lang->get('page-loading-text'));

//显示
if ($obj_install->installed()) {
    switch ($str_action) {
        case 'login':
            //是否选了记住账号
            $boo_remember = isset($_POST['lg-remember']) ? true : false;
            //验证账号密码
            if ($obj_user->login($_POST['lg-username'], $_POST['lg-passcode'], $boo_remember)) {
                header('Location: index.php');
            } else {
                $obj_view->sign('error', true);
                $obj_view->make('login.php');
            }
            break;
        case 'logout':
            $obj_user->logout();
            $obj_view->make('login.php');
            break;
        default :
            $obj_view->sign('_TPL_HT', $obj_lang->get('navbar-text-login'));
            $obj_view->make('login.php');
            break;
    }
} else {
    header('Location:welcome.php');
}
