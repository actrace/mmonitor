<?php

/**
 * 图标数据输出类.
 * 只能处理来自Dber的数据.
 * 产出的数据用于前端Highcharts绘图
 * 
 * @copyright (c) 2013, mMonitor.org
 * @version 1.0
 * @author Actrace
 * @date 2013-10-16 14:50:29
 */
class Bkex_charts extends Basekit {

    /**
     * 生成CPU数据.
     * @param type $nums
     * @param type $data
     * @return boolean
     */
    public function cpu($data) {
        $arr_tmp_usr  = array();
        $arr_tmp_sys  = array();
        $arr_tmp_wa   = array();
        $arr_tmp_idle = array();
        foreach ($data as $row) {
            if (is_array($row)) {
                $arr_tmp_usr[]  = array($row['time'] * 1000, (float) $row['usr']);
                $arr_tmp_sys[]  = array($row['time'] * 1000, (float) $row['sys']);
                $arr_tmp_wa[]   = array($row['time'] * 1000, (float) $row['wa']);
                $arr_tmp_idle[] = array($row['time'] * 1000, (float) $row['idle']);
            } else {
                $arr_tmp_usr[]  = array($row * 1000, 0.0);
                $arr_tmp_sys[]  = array($row * 1000, 0.0);
                $arr_tmp_wa[]   = array($row * 1000, 0.0);
                $arr_tmp_idle[] = array($row * 1000, 0.0);
            }
        }
        return array(
            'usr'  => $arr_tmp_usr,
            'sys'  => $arr_tmp_sys,
            'wa'   => $arr_tmp_wa,
            'idle' => $arr_tmp_idle,
        );
    }
    
    /**
     * 生成内存数据
     * @param type $data
     * @return int
     */
    public function mem($data) {
        $arr_tmp = array();
        foreach ($data as $row) {
            if (is_array($row)) {
                $arr_tmp['used'][]    = array($row['time'] * 1000, ceil(($row['use'] - $row['scached'] - $row['buffer']) / 1024));
                $arr_tmp['cached'][]  = array($row['time'] * 1000, ceil($row['scached'] / 1024));
                $arr_tmp['buffers'][] = array($row['time'] * 1000, ceil($row['buffer'] / 1024));
                $arr_tmp['free'][]    = array($row['time'] * 1000, ceil($row['free'] / 1024));
            } else {
                $arr_tmp['used'][]    = array($row * 1000, 0);
                $arr_tmp['cached'][]  = array($row * 1000, 0);
                $arr_tmp['buffers'][] = array($row * 1000, 0);
                $arr_tmp['free'][]    = array($row * 1000, 0);
            }
        }
        return $arr_tmp;
    }
    
    /**
     * 生成负载数据
     * @param type $data
     * @return type
     */
    public function load($data) {
        $arr_tmp = array();
        foreach ($data as $row) {
            if (is_array($row)) {
                $arr_tmp[1][] = array($row['time'] * 1000, (float) $row['a']);
                $arr_tmp[2][] = array($row['time'] * 1000, (float) $row['b']);
                $arr_tmp[3][] = array($row['time'] * 1000, (float) $row['c']);
            } else {
                $arr_tmp[1][] = array($row * 1000, 0);
                $arr_tmp[2][] = array($row * 1000, 0);
                $arr_tmp[3][] = array($row * 1000, 0);
            }
        }
        return $arr_tmp;
    }
    
    /**
     * 生成网络负载数据
     * @param type $data
     * @return int
     */
    public function network($data) {
        $arr_device = array();
        foreach ($data as $key => $val) {
            $arr_device[$key]['id']     = md5($val['device'] . $val['ipv4']);
            $arr_device[$key]['device'] = $val['device'];
            $arr_device[$key]['ipv4']   = $val['ipv4'];
            foreach ($val['data'] as $row) {
                if (is_array($row)) {
                    $arr_device[$key]['rx'][] = array($row['time'] * 1000, (int) ceil($row['rxrate'] / 1024));
                    $arr_device[$key]['tx'][] = array($row['time'] * 1000, (int) ceil($row['txrate'] / 1024));
                } else {
                    $arr_device[$key]['rx'][] = array($row * 1000, 0);
                    $arr_device[$key]['tx'][] = array($row * 1000, 0);
                }
            }
        }
        return $arr_device;
    }
    
    /**
     * 生成磁盘负载数据
     * @param type $data
     * @return int
     */
    public function disk($data){
        $arr_disk = array();
        foreach ($data as $key => $val) {
            $arr_disk[$key]['onid']     = strtoupper($val['onid']);
            $arr_disk[$key]['on']   = $val['on'];
            foreach ($val['data'] as $row) {
                if (is_array($row)) {
                    $arr_disk[$key]['used'][] = array($row['time'] * 1000, (float) round($row['used'] / 1024/1024/1024,2));
                    $arr_disk[$key]['avail'][] = array($row['time'] * 1000, (float) round($row['avail'] / 1024/1024/1024,2));
                } else {
                    $arr_disk[$key]['used'][] = array($row * 1000, 0);
                    $arr_disk[$key]['avail'][] = array($row * 1000, 0);
                }
            }
        }
        return $arr_disk;
    }
}