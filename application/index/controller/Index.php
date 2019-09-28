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

    public function hello()
    {
        //header('Access-Control-Allow-Origin:http://test.com');
        //file_put_contents('./swoole.log','qqq',FILE_APPEND);
        //echo '999';
        //$jsonp = $_GET['callback'];//get接收jsonp自动生成的函数名
        //$arr = array(
        //    'id' => 'asdf',
        //    'name' => 'eric'
        //);
        //echo $jsonp.'('. json_encode($arr). ')'; //jsonp函数名包裹json数据
        echo 'swoole.tankhui.cn ,请求参数:' . json_encode($_GET);
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
