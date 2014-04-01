<?php

/**
 * 用户操作
 * 
 * @copyright (c) 2013, mMonitor.org
 * @version 1.0
 * @author Actrace
 * @date 2013-10-08 09:52:49
 */
class Bkex_user extends Basekit{
    
    public $userlist = array();
    public $islogin = false;
    
    public function __construct($path_userlist) {
        session_start();
        if(isset($_SESSION['user'])){
            $user =  $_SESSION['user'];
            if($user!==0){
                $this->islogin = true;
            }
        }else{
            $_SESSION['user']=0;
        }
        if(file_exists($path_userlist)){
            $this->userlist = include($path_userlist);
            return true;
        }
        return false;
    }
    
    public function islogin(){
        return $this->islogin;
    }
    
    public function login($username,$passcode,$remember=false){
        if(isset($this->userlist[$username])){
            if($this->userlist[$username]==$passcode){
                //登陆成功
                $_SESSION['user'] = $username;
                if($remember){
                    $cookies_life_time = 180 * 3600;
                    setcookie(session_name() ,session_id(), time() + $cookies_life_time);
                }
                return true;
            }
        }
        return false;
    }
    
    public function logout(){
        $_SESSION['user']=0;
        return true;
    }
}