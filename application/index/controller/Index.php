<?php
namespace app\index\controller;

class Index
{
    public function index()
    {
        //print_r($_GET);
        //echo '999998877';
        return '';
    }

    public function hello($name = 'ThinkPHP5')
    {
        file_put_contents('./swoole.log','qqq',FILE_APPEND);
        echo '999';
        //return 'hello,' . $name;
    }

    public function sendSms(){

        require_once __DIR__. '/../../../vendor/sms/api_demo/SmsDemo.php';
        $params = $_GET;
        //print_r($params);
        try{
            $sms = \SmsDemo::sendSms($params['phone'],$params['name']);
            print_r($sms);
        } catch (\Exception $e) {
            print_r($e->getMessage());
        }

    }
}
