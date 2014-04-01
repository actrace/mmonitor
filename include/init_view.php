<?php
/**
 * 模板配置文件 - 及其初始化
 * 
 * @copyright (c) 2013, mMonitor.org
 * @version 1.0
 * @author Actrace
 * @date 2013-10-18 20:06:22
 */
//载入文件
include_once PATH_BASEKIT_VIEW;
$obj_view = new Bk_View(PATH_ROOT.'tpl/3/');
//预置一些变量
$obj_view->sign('_TPL', 'tpl/3/');
$obj_view->sign('_TPL_PANNEL',false);