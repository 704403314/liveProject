<?php
namespace app\index\controller;

class Send extends Util
{

    /**
     * 发送验证码
     */
    public function index(){
        //require_once __DIR__. '/../../../vendor/sms/api_demo/SmsDemo.php';
        //$phoneNum = request()->get('phone_num',0,'intval');
        $phoneNum = intval($_GET['phone_num']);
        //print_r($phoneNum);
        if (empty($phoneNum)) {
            return Util::show(config('code.error'),'error');

        }
        // 生成随机数
        $code = rand(1000,9999);

        //print_r($phoneNum,11);
        $taskData = [
            'method' => 'sendSms',
            'data' => [
                'phone' => $phoneNum,
                'code' => $code,
            ]

        ];
        //print_r($_POST);
        $_POST['http_server']->task($taskData);
        return Util::show(config('code.success'),'ok');
        //try{
        //    $sms = \app\common\lib\sms\api_demo\SmsDemo::sendSms($phoneNum,$code);
        //
        //} catch (\Exception $e) {
        //    //print_r($e->getMessage());
        //    return Util::show(config('code.error'),'阿里大鱼异常');
        //}


        //if ($sms->Code == 'OK') {
        //    // redis
        //    $redis = new \Swoole\Coroutine\Redis();
        //    $redis->connect(config('redis.host'),config('redis.port'));
        //    $redis->set("sms_" . $phoneNum,$code,120000);
        //    return Util::show(config('code.success'),'ok');
        //
        //} else {
        //    return Util::show(config('code.error'),'验证码发送失败');
        //
        //}


    }
}
