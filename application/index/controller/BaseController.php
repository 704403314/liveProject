<?php
/**
 * Created by PhpStorm.
 * User: hehui
 * Date: 2019/7/20
 * Time: 上午11:09
 */
namespace app\index\controller;
use app\common\controller\Util;
use app\common\lib\Redis;
use app\common\lib\redis\Predis;


class BaseController
{


    public function __construct()
    {
        if (!empty($_COOKIE['user'])) {
            $userInfo = Predis::getInstance()->get(Redis::userKey($_COOKIE['user']));
            if (!empty($userInfo)) {
                return true;
            } else {
                return Util::show('401','未登录');exit;
            }
        } else {
            return Util::show('401','未登录');exit;
        }
    }
}