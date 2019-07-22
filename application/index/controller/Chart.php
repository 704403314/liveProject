<?php
namespace app\index\controller;
//use app\index\controller\BaseController;
use app\common\lib\redis\Predis;
use app\common\lib\Redis;

class Chart
{
    public function __construct()
    {
    }

    /**
     * 发送评论
     */
    public function index()
    {
        // 登录判断
        $userInfo = \app\common\BaseLogic::getInstance()->checkLogin();
        if (empty($userInfo)) {
            return Util::show('401','未登录');
        }
        //return Util::show('401','error');
        //echo json_encode(['aa'=>1]);
        if (empty($_POST['game_id'])) {
            return Util::show(config('code.error'),'error');
        }
        if (empty($_POST['content'])) {
            return Util::show(config('code.error'),'error');
        }
        //print_r($_POST);
        $userInfo = json_decode($userInfo,true);
        $res = \app\index\logic\LogicChat::getInstance()->pushChat($userInfo);
        if ($res['code'] == 0) {
            return Util::show(config('code.success'),'发送成功');
        } else {
            return Util::show(config('code.error'),$res['msg']);
        }

    }

    /**
     * 验证登录
     */
    public function checkLogin()
    {
        $userInfo = \app\common\BaseLogic::getInstance()->checkLogin();
        if (empty($userInfo)) {
            return Util::show('401','未登录');
        } else {
            return Util::show(config('code.success'),'已登陆');
        }
    }



}
