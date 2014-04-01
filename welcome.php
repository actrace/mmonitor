<?php

/**
 * 欢迎界面
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
    $obj_view->sign('_TPL_PL', $obj_lang->get('page-loading-text'));
    $obj_view->sign('_TPL_CL_CSL', $obj_lang->get('welcome-clicklogo'));
    $obj_view->sign('_TPL_CL_SLF', $obj_lang->get('welcome-clf'));
    $obj_view->make('welcome.php');
}else{
    header('Location:index.php');
}