<?php

/**
 * 错误控制器
 * 用于处理运行中发生的各种错误
 * @copyright (c) 2013, Maxfs.org
 * @version 1.0
 * @author Actrace
 * @date 2013-10-13 10:02:46
 */
class Baserror {

    static $cfg_path, $cfg_name, $cfg_display, $cfg_log2file, $define_err, $cfg_runmodel;

    /**
     * 初始化设置
     * @param type $path    |错误日志目录
     * @param type $name    |错误日志名字
     * @param type $display |是否显示信息
     * @param type $log2file|是否写入文件
     * @return boolean
     */
    final static public function set($runmodel, $path, $name, $display = true, $log2file = true) {
        error_reporting(E_ALL);
        register_shutdown_function(array('Baserror', 'fatal'));
        set_error_handler(array('Baserror', 'corer'));
        self::$cfg_path = $path;
        self::$cfg_name = '['.  date('Y-m-d',time()).']'.$name;
        self::$cfg_display = $display;
        self::$cfg_log2file = $log2file;
        self::$cfg_runmodel = $runmodel == 'CLI' ? 'CLI' : 'CGI';
        self::$define_err = array();
        return true;
    }

    final static public function loger($msg) {
        $x = debug_backtrace();
        self::write("[{$x[0]['file']}][{$x[0]['line']}]{$msg}");
        return true;
    }

    final static public function fatal() {
        if ($e = error_get_last()) {
            self::corer($e['type'], '[Shut Down!]' . $e['message'], $e['file'], $e['line']);
        }
        self::end();
        exit;
    }

    final static public function write($msg) {
        $time = (microtime(true) * 1000);
        $t1   = substr($time, 0, 10);
        $t2   = substr($time, 10, 3);
        $t3   = self::bytes_to_size(memory_get_usage(TRUE));
        $msg  = '[' . date('h:i:s', $t1) . "][{$t2}][{$t3}] {$msg}";
        self::$define_err[] = $msg;
        return true;
    }

    final static public function corer($errno, $errstr, $errfile, $errline) {
        switch ($errno) {
            case E_ERROR:
            case E_PARSE:
            case E_CORE_ERROR:
            case E_COMPILE_ERROR:
            case E_USER_ERROR:
                $errorStr = "[{$errfile}][{$errline}]{$errstr}";
                self::write($errorStr);
                break;
            case E_STRICT:
            case E_USER_WARNING:
            case E_USER_NOTICE:
            default:
                $errorStr = "[{$errfile}][{$errline}]{$errstr}";
                self::write($errorStr);
                break;
        }
        return true;
    }

    final static public function end() {
        if (self::$define_err) {
            foreach (self::$define_err as $e) {
                if (self::$cfg_runmodel == 'CLI') {
                    if (self::$cfg_display) {
                        self::nprint($e);
                    }
                    if (self::$cfg_log2file) {
                        file_put_contents(self::$cfg_path . self::$cfg_name, $e . "\r\n", FILE_APPEND);
                    }
                } elseif (self::$cfg_runmodel == 'CGI') {
                    if (self::$cfg_display) {
                        self::bprint($e);
                    }
                    if (self::$cfg_log2file) {
                        file_put_contents(self::$cfg_path . self::$cfg_name, $e . "\r\n", FILE_APPEND);
                    }
                }
            }
        }
    }

    /**
     * 行打印字符串，多用于CLI模式。
     * 此函数将在输出字符串的时候自动给末尾加上换行符
     * @return boolean
     */
    final static public function nprint($str) {
        echo $str . "\n";
        return true;
    }

    /**
     * 行打印字符串，多用于CGI模式。
     * 此函数将在输出字符串的时候自动给末尾加上HTML换行符
     * @return boolean
     */
    final static public function bprint($str) {
        echo $str . "</br>\n";
        return true;
    }
    
    final static public function bytes_to_size($size, $digits = 2) {
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
