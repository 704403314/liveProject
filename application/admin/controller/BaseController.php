<?php
/**
 * Created by PhpStorm.
 * User: hehui
 * Date: 2019/7/20
 * Time: 上午11:09
 */
namespace app\admin\controller;
class BaseController
{


    public function checkEmpty($field,$params)
    {
        foreach ($field as $key=>$val) {
            if (empty($params[$key])) {
                return ['code' => 1, 'msg'=>$val . '不能为空'];
            }
        }
        return ['code'=>0];

    }
}