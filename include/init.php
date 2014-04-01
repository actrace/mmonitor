<?php
/**
 * 程序运行配置文件
 * 
 * @copyright (c) 2013, mMonitor.org
 * @version 1.0.1
 * @author Actrace
 * @date 2013-09-28 17:00:05
 */

//设置时区
date_default_timezone_set('Asia/Shanghai');

//版本号
define('VERSION_CODE',2);

//设置采集器日志控制
define('DEAMON_LOG_SHOW',false);//是否显示日志消息
define('DEAMON_LOG_FILE',true);//是否保存日志消息

//路径设定
define('PATH_ROOT', (__DIR__) . '/../');
define('PATH_INC',PATH_ROOT.'include/');
define('PATH_DATA',PATH_ROOT.'data/');
define('PATH_LOG',PATH_ROOT.'log/');
define('PATH_BASEKIT',PATH_ROOT.'basekit/');
define('PATH_BASEKIT_DB',PATH_BASEKIT.'Bk_db.php');
define('PATH_BASEKIT_DT',PATH_BASEKIT.'Bkex_dt.php');
define('PATH_BASEKIT_DBER',PATH_BASEKIT.'Bkex_dber.php');
define('PATH_BASEKIT_USER',PATH_BASEKIT.'Bkex_user.php');
define('PATH_BASEKIT_LANG',PATH_BASEKIT.'Bkex_lang.php');
define('PATH_BASEKIT_VIEW',PATH_BASEKIT.'Bk_view.php');
define('PATH_BASEKIT_SERVER',PATH_BASEKIT.'Bkex_server.php');
define('PATH_BASEKIT_CHARTS',PATH_BASEKIT.'Bkex_charts.php');
define('PATH_BASEKIT_INSTALL',PATH_BASEKIT.'Bkex_install.php');
define('PATH_LANG_EN',PATH_INC.'lang_en.php');

//需要自动载入
include_once PATH_BASEKIT.'Basekit.php';
include_once PATH_BASEKIT.'Baserror.php';