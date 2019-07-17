<?php
namespace app\common\lib;
/**
 * Created by PhpStorm.
 * User: hehui
 * Date: 2019/6/12
 * Time: 下午8:54
 */
class Redis
{
    static $smsKey = 'sms_';
    static $userKey = 'user_';
    public static function smsKey($phone)
    {
        return self::$smsKey . $phone;
    }

    public static function userKey($phone)
    {
        return self::$userKey . $phone;
    }

}

