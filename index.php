<?php

/**
 * 日志模式
 * 
 * @copyright (c) 2013, mMonitor.org
 * @version 1.0
 * @author Actrace
 * @date 2013-10-18 20:15:50
 */
//载入一些玩意
include_once 'include/init.php';
include_once 'include/init_view.php';
include_once 'include/init_user.php';
include_once 'include/init_lang.php';
include_once 'include/init_install.php';

//过渡页模板填充
$obj_view->sign('_TPL_PL', $obj_lang->get('page-loading-text'));
//检查是否安装
if ($obj_install->installed()) {
    //检查是否登陆
    if ($obj_user->islogin()) {
        //已登录,抓取服务器列表
        include_once PATH_BASEKIT_SERVER;
        $obj_server = new Bkex_server;
        $arr_server = $obj_server->getservers();
        //模板操作
        $obj_view->sign('_TPL_HT', $obj_lang->get('navbar-text-index'));
        $obj_view->sign('_TPL_TZ_1', $obj_lang->get('index-time-zoom-1'));
        $obj_view->sign('_TPL_TZ_2', $obj_lang->get('index-time-zoom-2'));
        $obj_view->sign('_TPL_PANNEL', true);
        $obj_view->sign('NAVINT', 1);
        $obj_view->sign('SERVER', $arr_server);
        $obj_view->make('index.php');
    } else {
        //未登录
        header('Location: login.php');
    }
} else {
    header('Location: welcome.php');
}
