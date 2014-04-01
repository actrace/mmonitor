<?php

/**
 * Basekit基本库,所有扩展库从本库扩展.
 * 
 * @copyright (c) 2013, Maxfs.org
 * @version 1.0.3
 * @author Actrace
 * @date 2013-10-13 08:59:28
 */

class Basekit{
    private $runlog_path = null;
    private $runlog_now = ''; //运行状态
    private $runlog_def = array();
    
    /**
     * 初始化日志路径
     * @param type $path
     */
    public function logpath($path=null) {
        $this->runlog_path=$path;
    }
    
    /**
     * 设定运行状
     * @return boolean
     */
    final public function logset($str_now){
        $this->runlog_now = $str_now;
        return true;
    }
    
    /**
     * 添加运行状态
     * @return boolean
     */
    final public function logput($str_def,$int_def,$str_des){
        $this->runlog_def[$str_def][0] = $int_def;
        $this->runlog_def[$str_def][1] = $str_des;
        return true;
    }
    
    /**
     * 获取运行状态码
     * @return mixed | 可能是false,或者是具体的运行信息.
     * @return boolean
     */
    final public function logcode() {
        if (isset($this->runlog_def[$this->runlog_now])) {
            return $this->runlog_def[$this->runlog_now][0];
        }
        return false;
    }

    /**
     * 获取运行状态信息
     * @return mixed | 可能是false,或者是具体的运行信息.
     * @return boolean
     */
    final public function logtext() {
        if (isset($this->runlog_def[$this->runlog_now])) {
            return $this->runlog_def[$this->runlog_now][1];
        }
        return false;
    }
    
    /**
     * 写入日志到文件中
     * @param string $msg
     * @return boolean
     */
    final public function logwrite($msg){
        if(!file_exists($this->runlog_path)){
            return false;
        }
        $time = (microtime(true) * 1000);
        $t1 = substr($time, 0, 10);
        $t2 = substr($time, 10, 3);
        $t3 = memory_get_usage(TRUE);
        $msg = '[' . date('Y-m-d h:i:s', $t1) . "][{$t2}][{$t3}] {$msg}";
        file_put_contents($this->runlog_path, $msg."\r\n", FILE_APPEND);
        return true;
    }
}
?>