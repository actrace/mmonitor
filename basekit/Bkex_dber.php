<?php

/**
 * 数据库控制类,读写记录.
 * 
 * @copyright (c) 2013, mMonitor.org
 * @version 1.0
 * @author Actrace
 * @date 2013-10-23 17:05:44
 */
class Bkex_Dber extends Basekit {

    public $path_db, $obj_db;
    
    /**
     * 初始化
     * @param type $configs | 配置文件数组
     * @param type $tmp     | 是否为Realtime模式
     * @param type $keep    | 是否要求保留数据(Realtime模式)
     * @return boolean
     */
    public function __construct($configs, $tmp = false, $keep = false) {
        if ($tmp) {
            //临时数据库
            $this->path_db = PATH_DATA . "{$configs['dir']}/log.tmpdb";
        } else {
            //永久数据库
            $this->path_db = PATH_DATA . "{$configs['dir']}/log.db";
        }
        //当启用了临时数据库的时候,如果keep不是true的话,删除临时数据库
        if (file_exists($this->path_db)) {
            if ($tmp===true&&$keep===false) {
                unlink($this->path_db);
            }
        }
        //如果目标数据库还没有创建,那么创建目标数据库.
        if (!file_exists($this->path_db)) {
            copy(PATH_DATA . 'log.tmp.db', $this->path_db);
            chmod($this->path_db, 0775);
        }
        //检查数据库支持库是否已经加载.Bastkit::db.
        if (!class_exists('Bk_db')) {
            return false;
        }
        //创建数据库连接
        $this->obj_db = new Bk_db('sqlite', $this->path_db, '');
        if ($this->obj_db->Connect()) {
            return true;
        } else {
            return false;
        }
    }

    /**
     * 日志模式 - 获取CPU记录
     * @param type $time_start | 起始时间
     * @param type $time_end   | 结束时间
     * @param type $rows       | 要求行数
     * @return type
     */
    public function log_get_cpu($time_start, $time_end, $rows) {
        //计算出一个间隔时间
        $int_time_demiter = ceil(($time_end - $time_start) / $rows);
        //循环查询数据库获取时间段数据
        $arr_tmp          = array();
        for ($i = 0; $i < $rows; $i++) {
            //处理时间
            $time_end   = $time_start + $int_time_demiter;
            $str_sql    = "select * from cpu where time>{$time_start} and time<{$time_end} limit 1";
            $time_start = $time_end;
            //开始查询
            $arr_data   = $this->obj_db->query($str_sql);
            if ($arr_data == false) {
                $arr_tmp[$i] = $time_start;
            } else {
                $arr_tmp[$i] = $arr_data[0];
            }
        }
        return $arr_tmp;
    }
    
    /**
     * 日志模式 - 获取内存记录
     * @param type $time_start | 起始时间
     * @param type $time_end   | 结束时间
     * @param type $rows       | 要求行数
     * @return type
     */
    public function log_get_mem($time_start, $time_end, $rows) {
        //计算出一个间隔时间
        $int_time_demiter = ceil(($time_end - $time_start) / $rows);
        //循环查询数据库获取时间段数据
        $arr_tmp          = array();
        for ($i = 0; $i < $rows; $i++) {
            //处理时间
            $time_end   = $time_start + $int_time_demiter;
            $str_sql    = "select * from memory where time>{$time_start} and time<{$time_end} limit 1";
            $time_start = $time_end;
            //开始查询
            $arr_data   = $this->obj_db->query($str_sql);
            if ($arr_data == false) {
                $arr_tmp[$i] = $time_start;
            } else {
                $arr_tmp[$i] = $arr_data[0];
            }
        }
        return $arr_tmp;
    }
    
    /**
     * 日志模式 - 获取负载记录
     * @param type $time_start | 起始时间
     * @param type $time_end   | 结束时间
     * @param type $rows       | 要求行数
     * @return type
     */
    public function log_get_load($time_start, $time_end, $rows) {
        //计算出一个间隔时间
        $int_time_demiter = ceil(($time_end - $time_start) / $rows);
        //循环查询数据库获取时间段数据
        $arr_tmp          = array();
        for ($i = 0; $i < $rows; $i++) {
            //处理时间
            $time_end   = $time_start + $int_time_demiter;
            $str_sql    = "select * from load where time>{$time_start} and time<{$time_end} limit 1";
            $time_start = $time_end;
            //开始查询
            $arr_data   = $this->obj_db->query($str_sql);
            if ($arr_data == false) {
                $arr_tmp[$i] = $time_start;
            } else {
                $arr_tmp[$i] = $arr_data[0];
            }
        }
        return $arr_tmp;
    }
    
    /**
     * 日志模式 - 获取磁盘记录
     * @param type $target     | 目标监控对象磁盘信息
     * @param type $time_start | 起始时间
     * @param type $time_end   | 结束时间
     * @param type $rows       | 要求行数
     * @return type
     */
    public function log_get_disk($target, $time_start, $time_end, $rows) {
        //计算出一个间隔时间
        $int_time_demiter = ceil(($time_end - $time_start) / $rows);
        //查询数据库究竟有多少个不同的ONID
        $arr_res          = array();
        foreach ($target as $row) {
            $arr_tmp              = array();
            //初始化时间
            $int_round_time_start = $time_start;
            $int_round_time_end   = $time_end;
            //循环拉取数据
            for ($i = 0; $i < $rows; $i++) {
                //处理时间
                $int_round_time_end   = $int_round_time_start + $int_time_demiter;
                $str_sql              = "select * from disk where time>{$int_round_time_start} and time<{$int_round_time_end} and onid='{$row['onid']}' limit 1";
                $int_round_time_start = $int_round_time_end;
                //开始查询
                $arr_data             = $this->obj_db->query($str_sql);
                if ($arr_data == false) {
                    $arr_tmp[$i] = $int_round_time_start;
                } else {
                    $arr_tmp[$i] = $arr_data[0];
                }
            }
            //组装结果
            $arr_res[] = array(
                'data' => $arr_tmp,
                'onid' => $row['onid'],
                'on'   => $row['on']
            );
        }
        return $arr_res;
    }

    /**
     * 日志模式 - 获取网络记录
     * @param type $target     | 目标监控对象网络信息
     * @param type $time_start | 起始时间
     * @param type $time_end   | 结束时间
     * @param type $rows       | 要求行数
     * @return type
     */
    public function log_get_network($target, $time_start, $time_end, $rows) {
        //计算出一个间隔时间
        $int_time_demiter = ceil(($time_end - $time_start) / $rows);
        //查询数据库究竟有多少个不同的ONID
        $arr_res          = array();
        foreach ($target as $row) {
            $arr_tmp              = array();
            //初始化时间
            $int_round_time_start = $time_start;
            $int_round_time_end   = $time_end;
            //循环拉取数据
            for ($i = 0; $i < $rows; $i++) {
                //处理时间
                $int_round_time_end   = $int_round_time_start + $int_time_demiter;
                $str_sql              = "select * from network where time>{$int_round_time_start} and time<{$int_round_time_end} and interface='{$row['device']}' limit 1";
                $int_round_time_start = $int_round_time_end;
                //开始查询
                $arr_data             = $this->obj_db->query($str_sql);
                if ($arr_data == false) {
                    $arr_tmp[$i] = $int_round_time_start;
                } else {
                    $arr_tmp[$i] = $arr_data[0];
                }
            }
            //组装结果
            $arr_res[] = array(
                'data'   => $arr_tmp,
                'ipv4'   => $row['ipv4'],
                'device' => $row['device']
            );
        }
        return $arr_res;
    }

    /**
     * 实时模式 - 获取CPU记录
     * @param type $time_start | 起始时间
     * @param type $time_end   | 结束时间
     * @param type $rows       | 要求行数
     * @return type
     */
    public function rt_get_cpu($time_start, $time_end, $rows) {
        //计算出一个间隔时间
        $int_time_demiter = ceil(($time_end - $time_start) / $rows);
        //先获取最后60条信息
        $str_sql  = "select * from cpu order by time desc limit {$rows}";
        $arr_data = $this->obj_db->query($str_sql);
        //循环查询数据库获取时间段数据
        $arr_tmp  = array();
        for ($i = 0; $i < $rows; $i++) {
            $time_end   = $time_start + $int_time_demiter;
            $time_start = $time_end;
            //开始查询
            if (isset($arr_data[$rows-$i-1])) {
                //覆盖时间属性
                $arr_data[$rows-$i-1]['time'] = $time_start;
                $arr_tmp[$rows-$i-1] = $arr_data[$rows-$i-1];
            } else {
                $arr_tmp[$rows-$i-1] = $time_start;
            }
        }
        return $arr_tmp;
    }

    /**
     * 实时模式 - 获取内存记录
     * @param type $time_start | 起始时间
     * @param type $time_end   | 结束时间
     * @param type $rows       | 要求行数
     * @return type
     */
    public function rt_get_mem($time_start, $time_end, $rows) {
        //计算出一个间隔时间
        $int_time_demiter = ceil(($time_end - $time_start) / $rows);
        //先获取最后60条信息
        $str_sql  = "select * from memory order by time desc limit {$rows}";
        $arr_data = $this->obj_db->query($str_sql);
        //循环查询数据库获取时间段数据
        $arr_tmp  = array();
        for ($i = 0; $i < $rows; $i++) {
            $time_end   = $time_start + $int_time_demiter;
            $time_start = $time_end;
            //开始查询
            if (isset($arr_data[$rows-$i-1])) {
                //覆盖时间属性
                $arr_data[$rows-$i-1]['time'] = $time_start;
                $arr_tmp[$rows-$i-1] = $arr_data[$rows-$i-1];
            } else {
                $arr_tmp[$rows-$i-1] = $time_start;
            }
        }
        return $arr_tmp;
    }

    /**
     * 实时模式 - 获取负载记录
     * @param type $time_start | 起始时间
     * @param type $time_end   | 结束时间
     * @param type $rows       | 要求行数
     * @return type
     */
    public function rt_get_load($time_start, $time_end, $rows) {
        //计算出一个间隔时间
        $int_time_demiter = ceil(($time_end - $time_start) / $rows);
        //先获取最后60条信息
        $str_sql  = "select * from load order by time desc limit {$rows}";
        $arr_data = $this->obj_db->query($str_sql);
        //循环查询数据库获取时间段数据
        $arr_tmp  = array();
        for ($i = 0; $i < $rows; $i++) {
            $time_end   = $time_start + $int_time_demiter;
            $time_start = $time_end;
            //开始查询
            if (isset($arr_data[$rows-$i-1])) {
                //覆盖时间属性
                $arr_data[$rows-$i-1]['time'] = $time_start;
                $arr_tmp[$rows-$i-1] = $arr_data[$rows-$i-1];
            } else {
                $arr_tmp[$rows-$i-1] = $time_start;
            }
        }
        return $arr_tmp;
    }

    /**
     * 实时模式 - 获取磁盘记录
     * @param type $target     | 目标监控对象网络信息
     * @param type $time_start | 起始时间
     * @param type $time_end   | 结束时间
     * @param type $rows       | 要求行数
     * @return type
     */
    public function rt_get_disk($target, $time_start, $time_end, $rows) {
        //计算出一个间隔时间
        $int_time_demiter = ceil(($time_end - $time_start) / $rows);
        //查询数据库究竟有多少个不同的ONID
        $arr_res          = array();
        foreach ($target as $row) {
            $arr_tmp              = array();
            //初始化时间
            $int_round_time_start = $time_start;
            $int_round_time_end   = $time_end;
            //获得数据
            $str_sql              = "select * from disk where onid='{$row['onid']}' order by time desc limit {$rows}";
            $arr_data             = $this->obj_db->query($str_sql);
            //循环拉取数据
            for ($i = 0; $i < $rows; $i++) {
                //处理时间
                $int_round_time_end   = $int_round_time_start + $int_time_demiter;
                $int_round_time_start = $int_round_time_end;
                //开始查询
                if (isset($arr_data[$rows-$i-1])) {
                    //覆盖时间属性
                    $arr_data[$rows-$i-1]['time'] = $int_round_time_start;
                    $arr_tmp[$rows-$i-1]          = $arr_data[$rows-$i-1];
                } else {
                    $arr_tmp[$rows-$i-1] = $int_round_time_start;
                }
            }
            //组装结果
            $arr_res[] = array(
                'data' => $arr_tmp,
                'onid' => $row['onid'],
                'on'   => $row['on']
            );
        }
        return $arr_res;
    }
    /**
     * 实时模式 - 获取网络记录
     * @param type $target     | 目标监控对象网络信息
     * @param type $time_start | 起始时间
     * @param type $time_end   | 结束时间
     * @param type $rows       | 要求行数
     * @return type
     */
    public function rt_get_network($target, $time_start, $time_end, $rows) {
        //计算出一个间隔时间
        $int_time_demiter = ceil(($time_end - $time_start) / $rows);
        //查询数据库究竟有多少个不同的ONID
        $arr_res          = array();
        foreach ($target as $row) {
            //初始化时间
            $int_round_time_start = $time_start;
            $int_round_time_end   = $time_end;
            $arr_tmp              = array();
            //获得数据
            $str_sql              = "select * from network where interface='{$row['device']}' order by time desc limit {$rows}";
            $arr_data             = $this->obj_db->query($str_sql);
            //循环拉取数据
            for ($i = 0; $i < $rows; $i++) {
                //处理时间
                $int_round_time_end   = $int_round_time_start + $int_time_demiter;
                $int_round_time_start = $int_round_time_end;
                //开始查询
                if (isset($arr_data[$rows-$i-1])) {
                    //覆盖时间属性
                    $arr_data[$rows-$i-1]['time'] = $int_round_time_start;
                    $arr_tmp[$rows-$i-1]          = $arr_data[$rows-$i-1];
                } else {
                    $arr_tmp[$rows-$i-1] = $int_round_time_start;
                }
            }
            //组装结果
            $arr_res[] = array(
                'data'   => $arr_tmp,
                'ipv4'   => $row['ipv4'],
                'device' => $row['device']
            );
        }
        return $arr_res;
    }
    
    /**
     * 写入CPU记录
     * @param type $cpu
     * @return boolean
     */
    public function log_cpu($cpu) {
        $int_time = time();
        $str_sql  = "insert into cpu values(null,'{$cpu['usr']}','{$cpu['sys']}','{$cpu['wa']}','{$cpu['idle']}',{$int_time})";
        $this->obj_db->query($str_sql);
        return true;
    }
    
    /**
     * 写入内存记录
     * @param type $mem
     * @return boolean
     */
    public function log_mem($mem) {
        $int_time = time();
        $str_sql  = "insert into memory values(null,'{$mem['mem_used']}','{$mem['mem_free']}','{$mem['mem_buffers']}','{$mem['swap_used']}','{$mem['swap_free']}','{$mem['mem_cached']}',{$int_time})";
        $this->obj_db->query($str_sql);
        return true;
    }
    
    /**
     * 写入负载记录
     * @param type $load
     * @return boolean
     */
    public function log_load($load) {
        $int_time = time();
        $str_sql  = "insert into load values(null,'{$load[1]}','{$load[2]}','{$load[3]}',{$int_time})";
        $this->obj_db->query($str_sql);
        return true;
    }
    
    /**
     * 写入磁盘记录
     * @param type $arr_disk
     * @return boolean
     */
    public function log_disk($arr_disk) {
        $int_time = time();
        foreach ($arr_disk as $disk) {
            $str_sql = "insert into disk values(null,'{$disk['onid']}','{$disk['size']}','{$disk['used']}','{$disk['avail']}',{$int_time})";
            $this->obj_db->query($str_sql);
        }
        return true;
    }
    
    /**
     * 写入网络记录
     * @param type $arr_network
     * @return boolean
     */
    public function log_network($arr_network) {
        $int_time = time();
        foreach ($arr_network as $net) {
            $str_sql = "insert into network values(null,'{$net['device_name']}','{$net['rx_speed']}','{$net['tx_speed']}',{$int_time})";
            $this->obj_db->query($str_sql);
        }
        return true;
    }

}

?>
