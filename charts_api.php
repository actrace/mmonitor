<?php

/**
 * 前端API接口 - 统计图部分
 * - CPU
 * - MEM
 * - DISK
 * - LOAD
 * - NETWORK
 * 
 * @copyright (c) 2013, mMonitor.org
 * @version 1.0
 * @author Actrace
 * @date 2013-10-23 14:52:52
 */
//载入文件
include_once 'include/init.php';
include_once PATH_BASEKIT_SERVER;
include_once PATH_BASEKIT_CHARTS;
include_once PATH_BASEKIT_DT;
include_once PATH_BASEKIT_DB;
include_once PATH_BASEKIT_DBER;
//忽略用户断开
ignore_user_abort(1);
//接收参数
$get_time_start = isset($_POST['time_start']) ? (int) strtotime($_POST['time_start']) : 0;
$get_time_end   = isset($_POST['time_end']) ? (int) strtotime($_POST['time_end']) : 0;
$get_realtime   = (int)$_POST['realtime']==1 ? true : false;
$get_realkeep   = (int)$_POST['realkeep']==1 ? true : false;
$get_lay_time   = isset($_POST['relay_time']) ? (int)$_POST['relay_time'] : 8;
$get_host       = $_POST['host_name'];
//设定错误控制
Baserror::set('CLI', PATH_LOG, 'api.log', true, true);
//创建对象
$obj_server     = new Bkex_server();
$arr_server     = $obj_server->getservers($get_host);
//结果集
$arr_json       = array(
    'data'   => array(),
    'status' => false,
);
//获取目标服务器
if ($arr_server !== false) {
    $arr_system = $obj_server->system_get($arr_server);
    $obj_charts = new Bkex_charts();
    //判断模式
    if ($get_realtime) {
        //实时模式,采集数据并入库
        $obj_dber                    = new Bkex_Dber($arr_server, $get_realtime, $get_realkeep);
        $obj_dt                      = new Bkex_Dt();
        $obj_dt->fetch($arr_server['url']);
        $arr_cpu                     = $obj_dt->cpu();
        $arr_mem                     = $obj_dt->mem();
        $arr_disk                    = $obj_dt->disk();
        $arr_load                    = $obj_dt->load();
        $arr_network                 = $obj_dt->network();
        $obj_dber->log_cpu($arr_cpu);
        $obj_dber->log_mem($arr_mem);
        $obj_dber->log_disk($arr_disk);
        $obj_dber->log_load($arr_load);
        $obj_dber->log_network($arr_network);
        //重新定义时间段
        $get_time_end = time();
        $get_time_start = time()-($get_lay_time*60);
        //获取目标数据
        $arr_json['data']['cpu']     = $obj_charts->cpu($obj_dber->rt_get_cpu($get_time_start, $get_time_end,30));
        $arr_json['data']['mem']     = $obj_charts->mem($obj_dber->rt_get_mem($get_time_start, $get_time_end,30));
        $arr_json['data']['load']    = $obj_charts->load($obj_dber->rt_get_load($get_time_start, $get_time_end,30));
        $arr_json['data']['disk']    = $obj_charts->disk($obj_dber->rt_get_disk($arr_system['disk'],$get_time_start, $get_time_end, 30));
        $arr_json['data']['network'] = $obj_charts->network($obj_dber->rt_get_network($arr_system['network'],$get_time_start, $get_time_end, 30));
        $arr_json['target']          = $get_host;
        $arr_json['status']          = true;
    } else {
        //日志模式
        $obj_dber                    = new Bkex_Dber($arr_server);
        //获取目标数据
        $arr_json['data']['cpu']     = $obj_charts->cpu($obj_dber->log_get_cpu($get_time_start, $get_time_end, 60));
        $arr_json['data']['mem']     = $obj_charts->mem($obj_dber->log_get_mem($get_time_start, $get_time_end, 60));
        $arr_json['data']['load']    = $obj_charts->load($obj_dber->log_get_load($get_time_start, $get_time_end, 60));
        $arr_json['data']['disk']    = $obj_charts->disk($obj_dber->log_get_disk($arr_system['disk'], $get_time_start, $get_time_end, 60));
        $arr_json['data']['network'] = $obj_charts->network($obj_dber->log_get_network($arr_system['network'], $get_time_start, $get_time_end, 60));
        $arr_json['target']          = $get_host;
        $arr_json['status']          = true;
    }
}
//打印结果集
echo json_encode($arr_json);