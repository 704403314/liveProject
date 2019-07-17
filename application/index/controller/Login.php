<?php
namespace app\index\controller;
use app\common\lib\redis\Predis;
use app\common\lib\Redis;
class Login extends Util
{
    public function index()
    {
        //print_r($_GET);
        $phone = intval($_GET['phone_num']);
        $code = intval($_GET['code']);
        if (empty($phone) || empty($code)) {
            return Util::show(config('code.error'),'phone or code empty');
        }
        // redis.so
        try {
            $key = Redis::smsKey($phone);
            //print_r($key);
            $redisCode = Predis::getInstance()->get($key);
        } catch (\Exception $e) {
            echo $e->getMessage();
        }

        if ($redisCode == $code) {
            $data = [
                'user' => $phone,
                'secKey' => md5(Redis::userKey($phone)),
                'time' => time(),
                'isLogin' => true,
            ];
            Predis::getInstance()->set(Redis::userKey($phone),$data);


            return Util::show(config('code.success'),'OK',$data);
        } else {
            return Util::show(config('code.error'),'code is error');
        }

    }
}
