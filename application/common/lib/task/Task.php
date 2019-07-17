<?php
/**
 * Created by PhpStorm.
 * User: hehui
 * Date: 2019/6/13
 * Time: 下午8:38
 */
namespace app\common\lib\task;
use app\common\lib\Redis;
use app\common\lib\redis\Predis;
class Task
{
    public function sendSms($data, $serv)
    {
        try{
            $sms = \app\common\lib\sms\api_demo\SmsDemo::sendSms($data['phone'],$data['code']);

        } catch (\Exception $e) {
            print_r($e->getMessage());
            return false;
        }

        if ($sms->Code == 'OK') {
            // redis
            Predis::getInstance()->set(Redis::$smsKey . $data['phone'],$data['code'],120000);

        } else {
            return true;

        }
    }

    public function pushLive($data, $serv)
    {
        $clients = Predis::getInstance()->sMembers(config('redis.live_game_key'));
        //print_r($clients);
        foreach ($clients as $fd) {
            $serv->push($fd,json_encode($data));
            //$ws->push($fd,json_encode(['aa'=>999]));
        }
    }
}
