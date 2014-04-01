<?php

/**
 * 服务器探针.测试支持CENTOS.REDHAT.
 *  - 获取服务器负载
 *  - 获取所有网卡状态
 *  - 获取CPU使用率
 *  - 获取磁盘使用率
 *  - 获取内存使用率
 * 该探针需要PHP支持EXEC函数,可以在CGI模式下运行,依赖系统内置的IFCONFI,TOP,UPTIME,DF工具获取数据.
 * 
 * @copyright (c) 2013, mMonitor.org
 * @version 1.0.1
 * @author Actrace
 * @date 2013-11-26 14:48:41
 */
if (runtime_check()) {
    echo json_encode(array(
        'Load'    => get_load(),
        'Network' => get_network_status(),
        'Memory'  => get_mem_status(),
        'Disk'    => get_disk_status(),
        'Cpu'     => get_cpu_status()
    ));
} else {
    echo 'need function[exec]';
}

function get_load() {
    exec('uptime', $return, $res);
    preg_match_all('|load average: (.*), (.*), (.*)|', $return[0], $macth);
    return $macth;
}

function get_network() {
    exec('ifconfig', $return, $res);
    $tmp     = '';
    $res     = array();
    $content = array();
    foreach ($return as $row) {
        if ($row != '') {
            $tmp .= $row;
        } else {
            $content[] = $tmp;
            $tmp       = '';
        }
    }
    return $content;
}

function get_network_status() {
    //创建
    $tmp      = array();
    //获取网络信息
    $content1 = get_network();
    sleep(1);
    $content2 = get_network();
    //开始生成数据
    foreach ($content1 as $key => $row) {
        $len                      = strpos($row, ' ');
        $device_name              = substr($row, 0, $len);
        //获取信息-接口名称
        $tmp[$key]['device_name'] = $device_name;
        //获取信息-网络速度
        preg_match_all('|RX bytes:(.*) \(.*\)  TX bytes:(.*) \(.*\)|', $content1[$key], $matches1);
        preg_match_all('|RX bytes:(.*) \(.*\)  TX bytes:(.*) \(.*\)|', $content2[$key], $matches2);
        $rx1                      = (int) $matches1[1][0];
        $rx2                      = (int) $matches2[1][0];
        $tx1                      = (int) $matches1[2][0];
        $tx2                      = (int) $matches2[2][0];
        $tmp[$key]['rx_speed']    = $rx2 - $rx1;
        $tmp[$key]['tx_speed']    = $tx2 - $tx1;
        //获取信息-总流量
        $tmp[$key]['rx_total']    = $rx2;
        $tmp[$key]['tx_total']    = $tx2;
        //获取信息-IPv4地址
        preg_match_all('|inet addr:(\S+)|', $content1[$key], $matches3);
        $tmp[$key]['ipv4']        = isset($matches3[1][0])?$matches3[1][0]:null;
    }
    return $tmp;
}

function get_disk_status() {
    $tmp = array();
    exec('df -P -h', $return, $res);
    foreach ($return as $key => $row) {
        if ($key != 0) {
            $data                    = explode(' ', preg_replace('/ +/', ' ', $row));
            $tmp[$key]['filesystem'] = $data[0];
            $tmp[$key]['size']       = $data[1];
            $tmp[$key]['used']       = $data[2];
            $tmp[$key]['avail']      = $data[3];
            $tmp[$key]['on']         = $data[5];
        }
    }
    return $tmp;
}

function get_cpu_status() {
    exec('top -b -n 2 -d 1 | grep -E "^(Cpu)"', $return, $res);
    $data        = $return[1];
    $data        = explode(' ', preg_replace('/ +/', ' ', $data));
    $tmp['usr']  = number_format(substr($data[1], 0, strpos($data[1], '%')), 2);
    $tmp['sys']  = number_format(substr($data[2], 0, strpos($data[2], '%')), 2);
    $tmp['idle'] = number_format(substr($data[4], 0, strpos($data[5], '%')), 2);
    $tmp['wa']   = number_format(substr($data[5], 0, strpos($data[6], '%')), 2);
    return $tmp;
}

function get_mem_status() {
    exec('top -b -n 1 | grep -E "^(Mem)"', $return1, $res);
    exec('top -b -n 1 | grep -E "^(Swap)"', $return2, $res);
    $data1              = $return1[0];
    $data2              = $return2[0];
    $data1              = explode(' ', preg_replace('/ +/', ' ', $data1));
    $data2              = explode(' ', preg_replace('/ +/', ' ', $data2));
    $tmp['mem_total']   = $data1[1];
    $tmp['mem_used']    = $data1[3];
    $tmp['mem_free']    = $data1[5];
    $tmp['mem_buffers'] = $data1[7];
    $tmp['swap_total']  = $data2[1];
    $tmp['swap_used']   = $data2[3];
    $tmp['swap_free']   = $data2[5];
    $tmp['mem_cached']  = $data2[7];
    return $tmp;
}

function runtime_check() {
    if (!function_exists('exec')) {
        return false;
    }
    return true;
}

?>