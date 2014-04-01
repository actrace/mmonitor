<?php
/**
 * 数据采集程序,CLI程序,定期采集数据.
 * 对于多个站点,派生进程进行采集.
 * 
 * @copyright (c) 2013, mMonitor.org
 * @version 1.0
 * @author Actrace
 * @date 2013-10-11 17:25:39
 */
//检查运行模式
if(php_sapi_name()!=='cli'){
    exit('Cli only.');
}
if(!function_exists('pcntl_fork')){
    exit('Need PCNTL support.');
}

//载入文件
include_once 'include/init.php';
include_once PATH_BASEKIT_SERVER;
include_once PATH_BASEKIT_DT;
include_once PATH_BASEKIT_DB;
include_once PATH_BASEKIT_DBER;

//设定错误控制
Baserror::set('CLI', PATH_LOG, 'deamon.log', DEAMON_LOG_SHOW, DEAMON_LOG_FILE);
Baserror::loger("守护进程正在运行.");
//开始创建对象maxfs
$obj_dt = new Bkex_Dt();
//获取所有可用服务器列表
$arr_servers = $obj_dt->getservers();
if($arr_servers===false){
    exit('No data.');
}
//创建新进程用于采集信息
$arr_pids = array();
//创建任务组
foreach($arr_servers as $server){
    //创建新进程用于采集数据
    $int_pid = pcntl_fork();
    
    if($int_pid===false){
        //进程创建失败
        Baserror::loger("任务子进程[{$server['name']}]创建失败.");
    }elseif($int_pid!=0){
        //父进程返回
        $arr_pids[] = $int_pid;
    }else{
        //重定向错误控制
        //Baserror::loger("任务子进程[{$server['name']}]创建成功.");
        Baserror::set('CLI', PATH_LOG, "deamon-woker[{$server['name']}].log",DEAMON_LOG_SHOW, DEAMON_LOG_FILE);
        //子进程,开始采集工作,获取服务器数据.
        if($obj_dt->fetch($server['url'])){
            //创建写数据对象
            $obj_dber = new Bkex_Dber($server);
            if($obj_dber===false){
                Baserror::loger("Dber can't start!");
                exit;
            }
            //获取信息
            $arr_cpu = $obj_dt->cpu();
            $arr_mem = $obj_dt->mem();
            $arr_disk = $obj_dt->disk();
            $arr_load = $obj_dt->load();
            $arr_network = $obj_dt->network();
            $obj_dber->log_cpu($arr_cpu);
            $obj_dber->log_mem($arr_mem);
            $obj_dber->log_disk($arr_disk);
            $obj_dber->log_load($arr_load);
            $obj_dber->log_network($arr_network);
            Baserror::loger("Task [{$server['name']}] finish!");
        }else{
            //数据抓取失败
            Baserror::loger("Fetch [{$server['name']}] false.");
        }
        exit;
    }
}

//Baserror::loger("任务进程组完成创建.开始计时.");
$int_runtime = 0;
//等待子进程
do{
    foreach($arr_pids as $key=>$val){
        //每隔一秒检查一次子进程状态
        if(pcntl_waitpid($val, $status, WNOHANG)>-1){
            //进程仍然在运行
            //Baserror::loger("子进程[$val]正在运行.");
        }else{
            //发生错误时返回-1,如果提供了 WNOHANG作为option（wait3可用的系统）并且没有可用子进程时返回0。
            //已经正常退出,移除PID数组
            unset($arr_pids[$key]);
            //Baserror::loger("子进程[$val]已经退出.");
        }
    }
    sleep(1);
    $int_runtime++;
}while(count($arr_pids)>0);
Baserror::loger("任务进程组运行结束.耗时{$int_runtime}秒.");
exit;
?>