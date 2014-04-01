<?php
/**
 * 安装界面
 * 
 * @copyright (c) 2013, mMonitor.org
 * @version 1.0
 * @author Actrace
 * @date 2013-10-26 17:43:00
 */
include_once 'include/init.php';
include_once 'include/init_view.php';
include_once 'include/init_lang.php';
include_once 'include/init_install.php';

//检查是否安装
if(!$obj_install->installed()){
    if(isset($_GET['install'])){
        $obj_install->install();
        header('Location:login.php');
        exit;
    }
    //支持检查
    if($obj_install->check_sqlite()){
        if($obj_install->check_rw()){
            if($obj_install->check_tpl()){
                $support = 0;
            }else{
                $support = 3;
            }
        }else{
            $support = 2;
        }
    }else{
        $support = 1;
    }
    //模板操作
    $obj_view->sign('_TPL_HT',$obj_lang->get('navbar-text-install'));
    $obj_view->sign('_TPL_PL', $obj_lang->get('page-loading-text'));
    $obj_view->sign('_TPL_INS_T1', $obj_lang->get('install-notice-1'));
    $obj_view->sign('_TPL_INS_T2', $obj_lang->get('install-notice-2'));
    $obj_view->sign('_TPL_INS_BTN', $obj_lang->get('install-btn'));
    $obj_view->sign('_TPL_PR', $obj_lang->get('install-check'));
    $obj_view->sign('_TPL_PR_1', $obj_lang->get('install-check-1'));
    $obj_view->sign('_TPL_PR_2', $obj_lang->get('install-check-2'));
    $obj_view->sign('_TPL_PR_3', $obj_lang->get('install-check-3'));
    $obj_view->sign('_TPL_PRM_1', $obj_lang->get('install-check-msg-1'));
    $obj_view->sign('_TPL_PRM_2', $obj_lang->get('install-check-msg-2'));
    $obj_view->sign('_TPL_PRM_3', $obj_lang->get('install-check-msg-3'));
    $obj_view->sign('_TPL_CHECK', $support);
    $obj_view->make('install.php');
}else{
    //未登录
    header('Location: index.php');
}