<?php
/**
 * Created by PhpStorm.
 * User: hehui
 * Date: 2019/6/12
 * Time: 下午8:34
 */
namespace app\common\lib\redis;

class Predis
{
    private static $_instance = null;

    public static function getInstance()
    {
        if (empty(self::$_instance)) {
            self::$_instance = new self();
        }
        return self::$_instance;
    }

    private function __construct()
    {
        $this->redis = new \Redis();
        $result = $this->redis->connect(config('redis.host'),config('redis.port'),config('redis.timeOut'));

        if ($result === false) {
            throw new \Exception('redis connect error');
        }
    }

    public function set($key,$value,$time=0)
    {
        if (empty($key)) {
            return '';
        }
        if (is_array($value)) {
            $value = json_encode($value);
        }
        if (!$time) {
            return $this->redis->set($key,$value);
        }
        return $this->redis->set($key,$value,$time);
    }

    public function get($key=null)
    {
        if (empty($key)) {
            return '';
        }
        return $this->redis->get($key);
    }

    /**
     * 有序集合
     * @param $key
     * @param $value
     * @return mixed
     */
    //public function sAdd($key,$value)
    //{
    //    return $this->redis->sAdd($key,$value);
    //}
    //
    //public function sRem($key,$value)
    //{
    //    return $this->redis->sRem($key,$value);
    //}

    //public function sMembers($key)
    //{
    //    return $this->redis->sMembers($key);
    //}

    public function __call($name, $arguments)
    {
        //return $this->redis->$name($arguments[0],$arguments[1]);
        return $this->redis->$name(...$arguments);
    }



}
