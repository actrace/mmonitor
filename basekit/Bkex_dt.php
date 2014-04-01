<?php

/**
 * 数据过滤组件
 * 格式化并规范输入数据.
 * -设定输入数据
 * -获取CPU占用率
 * -获取MEM占用率
 * -获取网络占用率
 * -获取磁盘占用率
 * -获取平均负载值
 * 
 * @copyright (c) 2013, mMonitor.org
 * @version 1.0
 * @author Actrace
 * @date 2013-10-08 09:52:49
 */
class Bkex_Dt extends Bkex_server {

    public function cpu() {
        if ($this->server_fetch_data !== false) {
            $data = $this->server_fetch_data['Cpu'];
            return array(
                'usr'  => $data['usr'],
                'sys'  => $data['sys'],
                'wa'   => $data['wa'],
                'idle' => $data['idle']
            );
        }
        return false;
    }

    public function mem() {
        if ($this->server_fetch_data !== false) {
            $data = $this->server_fetch_data['Memory'];
            return array(
                'mem_total'   => $this->get_number($data['mem_total']),
                'mem_used'    => $this->get_number($data['mem_used']),
                'mem_free'    => $this->get_number($data['mem_free']),
                'mem_buffers' => $this->get_number($data['mem_buffers']),
                'swap_total'  => $this->get_number($data['swap_total']),
                'swap_used'   => $this->get_number($data['swap_used']),
                'swap_free'   => $this->get_number($data['swap_free']),
                'mem_cached'  => $this->get_number($data['mem_cached']),
            );
        }
        return false;
    }

    public function network() {
        if ($this->server_fetch_data !== false) {
            $data    = $this->server_fetch_data['Network'];
            $arr_tmp = array();
            foreach ($data as $row) {
                if (!empty($row['ipv4'])) {
                    $arr_tmp[] = array(
                        'device_name' => $row['device_name'],
                        'rx_speed'    => $row['rx_speed'],
                        'tx_speed'    => $row['tx_speed'],
                        'rx_total'    => $row['rx_total'],
                        'tx_total'    => $row['tx_total'],
                        'ipv4'        => $row['ipv4']
                    );
                }
            }
            return $arr_tmp;
        }
        return false;
    }

    public function disk() {
        if ($this->server_fetch_data !== false) {
            $arr_disk = $this->server_fetch_data['Disk'];
            $arr_tmp  = array();
            foreach ($arr_disk as $mount) {
                if (!empty($mount['on'])&&$mount['filesystem']!=='tmpfs') {
                    //具备这个属性说明是正确挂载了.
                    $data1     = $this->size_to_bytes($mount['size']);
                    $data2     = $this->size_to_bytes($mount['used']);
                    $data3     = $this->size_to_bytes($mount['avail']);
                    $arr_tmp[] = array(
                        'on'    => $mount['on'],
                        'onid'  => substr(md5($mount['on']), 0, 8),
                        'size'  => $data1['bytes'],
                        'used'  => $data2['bytes'],
                        'avail' => $data3['bytes'],
                    );
                }
            }
            return $arr_tmp;
        }
        return false;
    }

    public function load() {
        if ($this->server_fetch_data !== false) {
            return array(
                1 => $this->server_fetch_data['Load'][1][0],
                2 => $this->server_fetch_data['Load'][2][0],
                3 => $this->server_fetch_data['Load'][3][0],
            );
        }
        return false;
    }

    public function get_number($base) {
        $preg = '|\d+|';
        preg_match($preg, $base, $matches);
        return $matches[0];
    }

    public function size_to_bytes($base) {
        //获取值的部分
        $preg     = '|[\d\.]+|';
        preg_match($preg, $base, $matches);
        $flo_base = $matches[0];
        //先把字符串转换成字节数
        $str_base = strtolower($base);
        $int_byte = 0;
        $int_size = 0;
        if (strpos($str_base, 'k')) {
            $int_byte = $flo_base * 1024;
            $int_size = 1;
        } elseif (strpos($str_base, 'm')) {
            $int_byte = $flo_base * 1024 * 1024;
            $int_size = 2;
        } elseif (strpos($str_base, 'g')) {
            $int_byte = $flo_base * 1024 * 1024 * 1024;
            $int_size = 3;
        } elseif (strpos($str_base, 't')) {
            $int_byte = $flo_base * 1024 * 1024 * 1024 * 1024;
            $int_size = 4;
        } else {
            $int_byte = $flo_base;
        }
        return array('bytes' => ceil($int_byte), 'size'  => $int_size);
    }

    public function bytes_to_size($size, $digits = 2) {
        $unit = array('', 'K', 'M', 'G', 'T', 'P');
        $base = 1024;
        $i    = floor(log($size, $base));
        $n    = count($unit);
        if ($i >= $n) {
            $i = $n - 1;
        }
        return round($size / pow($base, $i), $digits) . ' ' . $unit[$i] . 'B';
    }

}

?>
