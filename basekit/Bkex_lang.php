<?php
/**
 * 语言控制组件
 * 
 * @copyright (c) 2013, mMonitor.org
 * @version 1.0
 * @author Actrace
 * @date 2013-11-05 01:12:56
 */
class Bkex_lang extends Basekit{
    public $set = 'en';
    public $lang = array();
    public function __construct($lang_set,$lang_lib) {
        $this->set = $lang_set;
        $this->lang = include($lang_lib);
        return true;
    }
    
    public function get($key){
        if(isset($this->lang[$key])){
            return $this->lang[$key];
        }
        return false;
    }
}