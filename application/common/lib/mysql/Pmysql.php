<?php
/**
 * Created by PhpStorm.
 * User: hehui
 * Date: 2019/6/21
 * Time: 上午11:09
 */
namespace app\common\lib\mysql;

class Pmysql
{
    private static $_instance = null;
    public function __construct()
    {
        $swoole_mysql = new Swoole\Coroutine\MySQL();
        $swoole_mysql->connect([
            'host' => config('mysql.host'),
            'port' => config('mysql.port'),
            'user' => config('mysql.user'),
            'password' => config('mysql.password'),
            'database' => config('mysql.database'),
        ]);
    }

    public static function getInstance()
    {
        if (empty(self::$_instance)) {
            self::$_instance = new self();
        }
        return self::$_instance;
    }
}

