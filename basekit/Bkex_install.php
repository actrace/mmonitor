<?php
/**
 * 安装控制器
 * 
 * @copyright (c) 2013, mMonitor.org
 * @version 1.0
 * @author Actrace
 * @date 2013-11-23 16:02:41
 */
class Bkex_install extends Basekit{
    
    public $path;
    
    public function __construct($path) {
        $this->path = $path;
    }
    
    public function installed(){
        $path = $this->path.'install.lock';
        return file_exists($path);
    }
    
    public function install(){
        $path = $this->path.'install.lock';
        file_put_contents($path, 'ok,installed.');
    }
    
    public function check_rw(){
        $path = $this->path.'testfile';
        $fp = fopen($path, 'w+');
        if(fwrite($fp, '1')!==false){
            if(fread($fp, 8)!==false){
                fclose($fp);
                return true;
            }
        }
        fclose($fp);
        return false;
    }
    
    public function check_sqlite(){
        if(extension_loaded('sqlite3')&&extension_loaded('pdo_sqlite')){
            return true;
        }
        return false;
    }
    
    public function check_tpl(){
        $path = $this->path.'log.tmp.db';
        return file_exists($path);
    }
}