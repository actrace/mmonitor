<?php
/**
 * 简单视图操作组件
 * 
 * @copyright (c) 2013, Maxfs.org
 * @version 1.0
 * @author Actrace
 * @date 2013-10-18 19:59:46
 */
class Bk_View extends Basekit{
    
    protected $tpl = '';   
    protected $var = array();
    protected $res = '';
    
    /**
     * 初始化
     * @param type $path
     */
    public function __construct($path){
        $this->logput('PAHT_404', 100, '模板主目录不存在.['.path.']');
        if(file_exists($path)){
            $this->tpl = $path;
            return true;
        }else{
            $this->logset('PAHT_404');
            return false;
        }
    }
    
    /**
     * 解析模板
     * @return boolean
     */
    public function make($filename,$display = true){
        $filepath = $this->tpl.$filename;
        if(file_exists($filepath)){
            extract($this->var);
            ob_start();
            ob_implicit_flush(0);
            include($filepath);
            $content = ob_get_clean();
            if($display){
                echo $content;
            }
            return $content;
        }else{
            $this->logput('PAHT_404', 100, '模板文件不存在.['.$filepath.']');
            $this->logset('PAHT_404');
            return false;
        }
    }
    
    /**
     * 写入变量
     * @param type $key
     * @param type $val
     * @return boolean
     */
    public function sign($key,$val){
        $this->var[$key] = $val;
        return true;
    }
    
    /**
     * 加载其他模板文件
     * @param type $path
     * @return boolean
     */
    public function load($path){
        $filepath = $this->tpl.$path;
        if(file_exists($filepath)){
            extract($this->var);
            include($filepath);
            return true;
        }else{
            $this->logput('PAHT_404', 100, '模板文件不存在.['.$filepath.']');
            $this->logset('PAHT_404');
            return false;
        }
    }
    
}
?>