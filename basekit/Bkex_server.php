<?php

/**
 * 服务器操作组件
 * .
 * -获取所有监控服务器
 * 
 * @copyright (c) 2013, mMonitor.org
 * @version 1.0.1
 * @author Actrace
 * @date 2013-11-26 15:43:47
 */
class Bkex_server extends Basekit {

    public $server_fetch_data = null;

    /**
     * 获取检测的服务器列表
     * @return boolean
     */
    public function getservers($dir_name = null) {
        $arr_list = glob(PATH_DATA . '*/config.php');
        $arr_tmp  = array();
        $arr_row1 = array();
        $arr_row2 = array();
        if (count($arr_list) > 0) {
            foreach ($arr_list as $path) {
                $arr_row1                 = include($path);
                $arr_row2['name']         = $arr_row1['name'];
                $arr_row2['dir']          = $arr_row1['dir'];
                $arr_row2['url']          = $arr_row1['url'];
                $arr_tmp[]                = $arr_row2;
                $path_db                  = PATH_DATA . "{$arr_row1['dir']}/log.db";
                $path_system              = PATH_DATA . "{$arr_row1['dir']}/system";
                //如果目标数据库还没有创建,那么创建目标数据库.
                if (!file_exists($path_db)) {
                    copy(PATH_DATA . 'log.tmp.db', $path_db);
                    chmod($path_db, 0775);
                }
                //如果目标系统信息没有收集,创建这个信息
                if (!file_exists($path_system)) {
                    $this->system_put($arr_row2);
                }
            }
            //如果指定了dir_name参数,则返回的是匹配的服务器信息,找不到则返回false.
            if ($dir_name !== null) {
                foreach ($arr_tmp as $key => $val) {
                    if ($dir_name == $val['dir']) {
                        return $arr_tmp[$key];
                    }
                }
                return false;
            }
            //End
            return $arr_tmp;
        } else {
            return false;
        }
    }

    public function system_put($server) {
        $this->fetch($server['url']);
        $path_system = PATH_DATA . "{$server['dir']}/system";
        $arr_tmp2    = array();
        $arr_tmp3    = array();
        foreach ($this->server_fetch_data['Disk'] as $key => $val) {
            if (!empty($val['on']) && $val['filesystem'] !== 'tmpfs') {
                //不接受TMPFS和没有挂载点的数据
                $arr_tmp2[$key]['on']   = $val['on'];
                $arr_tmp2[$key]['onid'] = substr(md5($val['on']), 0, 8);
            }
        }
        foreach ($this->server_fetch_data['Network'] as $key => $val) {
            if (!empty($val['ipv4'])) {
                $arr_tmp3[$key]['device'] = $val['device_name'];
                $arr_tmp3[$key]['ipv4']   = $val['ipv4'];
            }
        }
        return file_put_contents($path_system, json_encode(array('network' => $arr_tmp3, 'disk'    => $arr_tmp2)), LOCK_EX);
    }

    public function system_get($server) {
        $path_system = PATH_DATA . "{$server['dir']}/system";
        return json_decode(file_get_contents($path_system), true);
    }

    public function fetch($url) {
        $this->server_fetch_data = file_get_contents($url);
        if ($this->server_fetch_data) {
            $this->server_fetch_data = json_decode($this->server_fetch_data, true);
            return true;
        } else {
            return false;
        }
    }

}