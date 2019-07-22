<?php
/**
 * Created by PhpStorm.
 * User: hehui
 * Date: 2019/7/20
 * Time: 上午10:36
 */
namespace app\common;
use app\common\lib\redis\Predis;
use app\common\lib\Redis;

class BaseLogic {

    static protected $instance = [];

    private function __construct()
    {

    }

    private function __clone()
    {
        // TODO: Implement __clone() method.
    }

    static public function getInstance($class = null)
    {
        if ($class === null) {
            $class = static::class;
        }

        if (!isset(self::$instance[$class])) {
            self::$instance[$class] = new $class;
        }

        return self::$instance[$class];

    }

    public function checkLogin()
    {
        // 登录判断
        if (!empty($_COOKIE['phone'])) {
            $userInfo = Predis::getInstance()->get(Redis::userKey($_COOKIE['phone']));
            if (!empty($userInfo)) {
                return $userInfo;
            } else {
                return [];
            }
        } else {
            return [];
        }
    }
}