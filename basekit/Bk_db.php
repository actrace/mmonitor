<?php

/**
 * 通用数据库操作组件
 * 支持MYSQL和SQLITE数据库,使用PDO来操作数据库.
 * 
 * @copyright (c) 2013, Maxfs.org
 * @version 1.0
 * @author Actrace
 * @date 2013-10-09 09:56:20
 */
class Bk_db extends Basekit{

    private $obj        = null;
    private $type       = null;
    private $init       = false;
    private $data       = null;
    private $host       = null;
    private $user       = null;
    private $pass       = null;
    private $name       = null;
    private $charset    = null;

    /**
     * 设置一个数据库工作连接参数
     * @param $type 链接的数据库类型，sqlite或者mysql。
     * @param $host 数据库地址，sqlite时，此处应该填数据库存放的路径。
     * @param $name 数据库名称
     * @param $user 数据库用户，sqlite时，此参数可设置为null。
     * @param $pass 数据库密码，sqlite时，此参数可设置为null。
     * @param $charset 数据库编码。默认为UTF-8。
     */
    public function __construct($type, $host, $name, $user = null, $pass = null, $charset = 'utf8') {
        $this->logput('DATABASE', 1, '');
        $this->logput('PREPARE', 102, '缓存目录不存在');
        $this->logput('UNKNOWDB', 101, '无法写入缓存目录');
        $this->logput('UNCONNECT', 201, '缓存已过期');
        $this->type    = strtolower($type);
        $this->name    = $name;
        $this->user    = $user;
        $this->host    = $host;
        $this->pass    = $pass;
        $this->charset = $charset;
    }

    /**
     * 连接数据库
     * 设定好数据库工作连接参数后，使用此方法才正式连接到数据库。
     * 在需要的地方执行连接再进行数据库操作能够降低程序整体执行时间，并提高执行效率。
     * 注意：在一个程序执行周期内长时间执行与数据库无关的操作可能会被数据库服务器断开连接，此时使用此方法进行重新连接。
     * @return boolean
     */
    public function connect() {
        //为不同的数据库设置不同的连接方式
        switch ($this->type) {
            //Connect到MYSQL数据库
            case'mysql':
                try {
                    $this->obj  = new PDO("mysql:dbname={$this->name};host={$this->host}", $this->user, $this->pass);
                    $this->Query("set names $this->charset");
                    $this->init = true;
                    return true;
                } catch (PDOException $e) {
                    $this->RunlogReset(array(1=>$e->getCode(),2=>$e->getMessage()));
                    return false;
                }
                break;
            //Connect到SQLITE数据库
            case'sqlite':
                try {
                    $this->obj  = new PDO("sqlite:{$this->host}{$this->name}");
                    $this->Query("PRAGMA encoding = \"{$this->charset}\"");
                    $this->init = true;
                    return true;
                } catch (PDOException $e) {
                    $this->runlogReset(array(1=>$e->getCode(),2=>$e->getMessage()));
                    return false;
                }
            default:
                $this->logset('UNKNOWDB');
                return false;
                break;
        }
    }

    /**
     * 执行查询语句
     * @param $Query SQL语句，需要注意的是，SQLITE和MYSQL支持的SQL标准是不一样的。
     * @return boolean
     */
    public function query($Query) {
        if ($this->obj) {
            $this->data = $this->obj->prepare($Query);
            if ($this->data===false) {
                $this->runlogReset($this->obj->errorInfo());
                return false;
            }
            if ($this->data->execute()) {
                $arr_tmp = array();
                while ($arr_res = $this->data->fetch(PDO::FETCH_ASSOC)) {
                    $arr_tmp[] = $arr_res;
                }
                return $arr_tmp;
            } else {
                $res = $this->data->errorInfo();
                $this->runlogReset($res);
                return false;
            }
        } else {
            return false;
        }
    }

    /**
     * 获取最后一个插入的ID
     * @param $col 指定一个字段，当表中有多个字段使用了自增字段时，可以使用此参数指定获取哪个字段。
     * @return int
     */
    public function getInertId($col = null) {
        return $this->obj->lastInsertId($col);
    }

    /**
     * 对本类错误处理的兼容处理,所有数据库错误归类到DATABASE.
     * @param type $error
     */
    private function runlogReset($error) {
        $this->logput('DATABASE', $error[1], $error[2]);
        $this->logset('DATABASE');
    }

}