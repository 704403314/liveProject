<?php
namespace app\index\controller;
use app\common\lib\redis\Predis;
use app\common\lib\Redis;
use think\Cookie;
use think\Session;

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
            //echo $e->getMessage();
        }

        if ($redisCode == $code) {
            $data = [
                'user' => $phone,
                'secKey' => md5(Redis::userKey($phone)),
                'time' => time(),
                'isLogin' => true,
            ];
            //setcookie('userinfo',json_encode($data),time()+86400*30);
            //session_start();
            //$session =new Session();
            //$cookie->set('userinfo',json_encode($data),86400*30);
            //$session->set('userinfo',json_encode($data));
            Predis::getInstance()->set(Redis::userKey($phone),$data);
            //\Http\Response->cookie();

            return Util::show(config('code.success'),'OK',$data);
        } else {
            return Util::show(config('code.error'),'code is error');
        }

    }

    //function Http\Response->cookie(string $key, string $value = '', int $expire = 0 , string $path = '/', string $domain  = '', bool $secure = false , bool $httponly = false);

}
