<?php
/**
 * Created by PhpStorm.
 * User: hehui
 * Date: 2019/7/20
 * Time: 上午10:34
 */
namespace app\admin\logic;
use app\common\BaseLogic;
use app\common\model\LiveTeam;

class LogicTeam extends BaseLogic {

    public function getList($params=[])
    {
        $model = new LiveTeam();

        $res = $model->where('deleted','=',0)->select()->toArray();

        $return = [];

        if (!empty($res)) {
            foreach ($res as $v) {
                $return[$v['id']] = $v;
            }
        }

        return $return;
    }
}