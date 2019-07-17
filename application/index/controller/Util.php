<?php
/**
 * Created by PhpStorm.
 * User: hehui
 * Date: 2019/6/11
 * Time: 下午8:55
 */

namespace app\index\controller;


class Util
{
    public static function show($status,$msg=null,$data=[])
    {
        $result = [
            'status' => $status,
            'message' => $msg,
            'data' => $data,
        ];
        echo json_encode($result);
    }
}